<?php

declare(strict_types=1);

namespace Fikusas\FileRead;

use Psr\SimpleCache\CacheInterface;


class FileRead
{
    const FILEPATH = "tex-hyphenation-patterns.txt";
    private $cache;


    /**
     * FileRead constructor.
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;

    }

    /**
     * @param string $path
     * @return array
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function readHyphenationPatterns($path = self::FILEPATH): array
    {
        $contents = file_get_contents($path);
        $data = [];
        if (!$this->cache->has("pattern")) {
            $data = explode("\n", $contents);
            $this->cache->set("pattern", $data);
            return $data;
        } else {
            return $this->cache->get("pattern");
        }
    }

}


