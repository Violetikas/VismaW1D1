<?php

//namespace Fikusas\Cache;
//
//
//use Psr\SimpleCache\CacheInterface;
//
//class FileCache implements CacheInterface
//{
//    private $values;
//    private $path;
//
//
//    public function __construct(string $path)
//    {
//        $this->path = $path;
//        $this->values = json_decode(file_get_contents($path), true);
//    }
//
//    public function __destruct()
//    {
//        file_put_contents($this->path, json_encode($this->values));
//    }
//
//
//    public function get($key, $default = null)
//    {
//        return $this->values[$key] ?? $default;
//    }
//
//
//    public function set($key, $value, $ttl = null)
//    {
//        $this->values[$key] = $value;
//    }
//
//
//    public function delete($key)
//    {
//        unset($this->values[$key]);
//    }
//
//
//    public function clear()
//    {
//        $this->values = [];
//    }
//
//
//    public function getMultiple($keys, $default = null)
//    {
//        foreach ($keys as $key) {
//            yield $this->get($key);
//        }
//    }
//
//    public function setMultiple($values, $ttl = null)
//    {
//        foreach ($values as $key => $value) {
//            $this->set($key, $value, $ttl);
//        }
//    }
//
//
//    public function deleteMultiple($keys)
//    {
//        foreach ($keys as $key) {
//            $this->delete($key);
//        }
//    }
//
//
//    public function has($key)
//    {
//        return array_key_exists($key, $this->values);
//    }
//
//
//
//
//}




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
    const INVALID_KEY_MESSAGE = "Key is not valid.";
    private $cachePath;
    private $defaultTTL;
    private $dirMode;
    private $fileMode;

    public function __construct($cachePath, $defaultTTL, $dirMode = 0775, $fileMode = 0664)
    {
        $this->defaultTTL = $defaultTTL;
        $this->dirMode = $dirMode;
        $this->fileMode = $fileMode;
        if (!file_exists($cachePath) && file_exists(dirname($cachePath))) {
            $this->mkdir($cachePath);
        }
        $path = realpath($cachePath);
        if ($path === false) {
            throw new InvalidArgumentException("Cache path does not exist: {$cachePath}");
        }
//        if (!is_writable($path . DIRECTORY_SEPARATOR)) {
//            throw new InvalidArgumentException("Cache path is not writable: {$cachePath}");
//        }
        $this->cachePath = $path;
    }

    public function get($key, $default = null)
    {
        $path = $this->getPath($key);
        $expiresAt = @filemtime($path);
        if (!$this->validateKey($key)) {
            throw new InvalidArgumentException(self::INVALID_KEY_MESSAGE);}
        else {

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
    }


    public function set($key, $value, $ttl = null)
    {
        $path = $this->getPath($key);
        $dir = dirname($path);
        if (!$this->validateKey($key)) {
            throw new InvalidArgumentException(self::INVALID_KEY_MESSAGE);}
        else {
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
        if (!is_array($keys) && !$keys instanceof Traversable) {
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
        if (!is_array($values) && !$values instanceof Traversable) {
            throw new InvalidArgumentException("keys must be either of type array or Traversable");
        }
        $ok = true;
        foreach ($values as $key => $value) {
            if (is_int($key)) {
                $key = (string)$key;
            }
            $this->validateKey($key);
            $ok = $this->set($key, $value, $ttl) && $ok;
        }
        return $ok;
    }

    public function deleteMultiple($keys)
    {
        if (!is_array($keys) && !$keys instanceof Traversable) {
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
        if (!$this->validateKey($key)) {
            throw new InvalidArgumentException(self::INVALID_KEY_MESSAGE);}

        else return $this->get($key, $this) !== $this;
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

    private function validateKey(string $key): bool
    {
        if (strlen($key) > 64) {
            return false;
        }
        return preg_match('/^[0-9A-Za-z_.]+$/', $key) == 1;
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

