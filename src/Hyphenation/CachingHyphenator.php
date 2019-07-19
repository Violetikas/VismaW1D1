<?php


namespace Fikusas\Hyphenation;


use Psr\SimpleCache\CacheInterface;

class CachingHyphenator implements WordHyphenatorInterface
{
    /** @var WordHyphenator */
    private $hyphenator;
    /** @var CacheInterface */
    private $cache;

    /**
     * CachingHyphenator constructor.
     * @param WordHyphenator $hyphenator
     * @param CacheInterface $cache
     */
    public function __construct(WordHyphenator $hyphenator, CacheInterface $cache)
    {
        $this->hyphenator = $hyphenator;
        $this->cache = $cache;
    }

    /**
     * @param string $word
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function hyphenate(string $word): string
    {
        $key = sha1($word);
        if (!($result = $this->cache->get($key))) {
            $result = $this->hyphenator->hyphenate($word);
            $this->cache->set($key, $result);
        }
        return $result;
    }
}
