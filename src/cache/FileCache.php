<?php


namespace Fikusas\Cache;


use DateInterval;
use http\Exception\InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use FilesystemIterator;
use Traversable;

class FileCache implements CacheInterface
{
    const PSR16RESERVED = '/\{|\}|\(|\)|\/|\\\\|\@|\:/u';
    private $cachePath;
    private $defaultTTL;
    private $dirMode;
    private $fileMode;

    public function __construct($cachePath, $defaultTTL, $dirMode = 0775, $fileMode = 0664)
    {
        $this->defaultTTL = $defaultTTL;
        $this->dirMode = $dirMode;
        $this->fileMode = $fileMode;
        if (! file_exists($cachePath) && file_exists(dirname($cachePath))) {
            $this->mkdir($cachePath); // ensure that the parent path exists
        }
        $path = realpath($cachePath);
        if ($path === false) {
            throw new InvalidArgumentException("cache path does not exist: {$cachePath}");
        }
        if (! is_writable($path . DIRECTORY_SEPARATOR)) {
            throw new InvalidArgumentException("cache path is not writable: {$cachePath}");
        }
        $this->cachePath = $path;
    }
    public function get($key, $default = null)
    {
        $path = $this->getPath($key);
        $expiresAt = @filemtime($path);

        if ($expiresAt === false) {
            return $default;
        }
        if ($this->getTime() >= $expiresAt) {
            @unlink($path);
            return $default;
        }

        $data = @file_get_contents($path);

        if ($data === false) {
            return $default;
        }
        if ($data === 'b:0;') {
            return false;
        }
        $value = @unserialize($data);
        if ($value === false) {
            return $default;
        }
        return $value;
    }


    public function set($key, $value, $ttl = null)
    {
        $path = $this->getPath($key);
        $dir = dirname($path);
        if (!file_exists($dir)) {
            // ensure that the parent path exists:
            $this->mkdir($dir);
        }
        $temp_path = $this->cachePath . DIRECTORY_SEPARATOR . uniqid('', true);
        if (is_int($ttl)) {
            $expires_at = $this->getTime() + $ttl;
        } elseif ($ttl instanceof DateInterval) {
            $expires_at = date_create_from_format("U", $this->getTime())->add($ttl)->getTimestamp();
        } elseif ($ttl === null) {
            $expires_at = $this->getTime() + $this->defaultTTL;
        } else {
            throw new InvalidArgumentException("invalid TTL: " . print_r($ttl, true));
        }
        if (false === @file_put_contents($temp_path, serialize($value))) {
            return false;
        }
        if (false === @chmod($temp_path, $this->fileMode)) {
            return false;
        }
        if (@touch($temp_path, $expires_at) && @rename($temp_path, $path)) {
            return true;
        }
        @unlink($temp_path);
        return false;
    }

    public function delete($key)
    {
        $this->validateKey($key);
        $path = $this->getPath($key);
        return !file_exists($path) || @unlink($path);
    }

    public function clear()
    {
        $success = true;
        $paths = $this->listPaths();
        foreach ($paths as $path) {
            if (!unlink($path)) {
                $success = false;
            }
        }
        return $success;
    }

    public function getMultiple($keys, $default = null)
    {
        if (! is_array($keys) && ! $keys instanceof Traversable) {
            throw new InvalidArgumentException("keys must be either of type array or Traversable");
        }
        $values = [];
        foreach ($keys as $key) {
            $values[$key] = $this->get($key) ?: $default;
        }
        return $values;
    }
    public function setMultiple($values, $ttl = null)
    {
        if (! is_array($values) && ! $values instanceof Traversable) {
            throw new InvalidArgumentException("keys must be either of type array or Traversable");
        }
        $ok = true;
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $key = (string) $key;
            }
            $this->validateKey($key);
            $ok = $this->set($key, $value, $ttl) && $ok;
        }
        return $ok;
    }
    public function deleteMultiple($keys)
    {
        if (! is_array($keys) && ! $keys instanceof Traversable) {
            throw new InvalidArgumentException("keys must be either of type array or Traversable");
        }
        $ok = true;
        foreach ($keys as $key) {
            $this->validateKey($key);
            $ok = $ok && $this->delete($key);
        }
        return $ok;
    }


    public function has($key)
    {
        return $this->get($key, $this) !== $this;
    }

    public function increment($key, $step = 1)
    {
        $path = $this->getPath($key);
        $dir = dirname($path);
        if (!file_exists($dir)) {
            $this->mkdir($dir);
        }
        $lockPath = $dir . DIRECTORY_SEPARATOR . ".lock";
        $lockHandle = fopen($lockPath, "w");
        flock($lockHandle, LOCK_EX);
        $value = $this->get($key, 0) + $step;
        $ok = $this->set($key, $value);
        flock($lockHandle, LOCK_UN);
        return $ok ? $value : false;
    }

    public function decrement($key, $step = 1)
    {
        return $this->increment($key, -$step);
    }


    public function cleanExpired()
    {
        $now = $this->getTime();
        $paths = $this->listPaths();
        foreach ($paths as $path) {
            if ($now > filemtime($path)) {
                @unlink($path);
            }
        }
    }


    protected function getPath($key)
    {
        $this->validateKey($key);
        $hash = hash("sha256", $key);
        return $this->cachePath
            . DIRECTORY_SEPARATOR
            . strtoupper($hash[0])
            . DIRECTORY_SEPARATOR
            . strtoupper($hash[1])
            . DIRECTORY_SEPARATOR
            . substr($hash, 2);
    }

    /**
     * @return int current timestamp
     */
    protected function getTime()
    {
        return time();
    }

    protected function listPaths()
    {
        $iterator = new RecursiveDirectoryIterator(
            $this->cachePath,
            FilesystemIterator::CURRENT_AS_PATHNAME | FilesystemIterator::SKIP_DOTS
        );
        $iterator = new RecursiveIteratorIterator($iterator);
        foreach ($iterator as $path) {
            if (is_dir($path)) {
                continue; // ignore directories
            }
            yield $path;
        }
    }

    protected function validateKey($key)
    {
        if (!is_string($key)) {
            $type = is_object($key) ? get_class($key) : gettype($key);
            throw new InvalidArgumentException("invalid key type: {$type} given, must be string.\n
            Must contain characters [0-9A-Za-z_.].Must be < 64 characters\"");
        }
        if ($key === "") {
            throw new InvalidArgumentException("invalid key: empty string given.\n
             Must contain characters [0-9A-Za-z_.]. Must be < 64 characters");
        }
        if ($key === null) {
            throw new InvalidArgumentException("invalid key: null given. \n
            Must contain characters [0-9A-Za-z_.]. Must be < 64 characters\"");
        }
        if (preg_match(self::PSR16RESERVED, $key, $match) === 1) {
            throw new InvalidArgumentException("invalid character in key: {$match[0]} .\n
             Must contain characters [0-9A-Za-z_.]. Must be < 64 characters\"") ;
        }
    }

    private function mkdir($path)
    {
        $parentPath = dirname($path);
        if (!file_exists($parentPath)) {
            $this->mkdir($parentPath);
        }
        mkdir($path);
        chmod($path, $this->dirMode);
    }


}

