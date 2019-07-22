<?php


namespace Fikusas\Hyphenation;


use Fikusas\DB\DatabaseConnector;
use Psr\SimpleCache\CacheInterface;
use Fikusas\DB\WordDB;
use PDO;
class CachingHyphenator implements WordHyphenatorInterface
{
    private $hyphenator;
    private $cache;
    private $db;

    public function __construct(DBHyphenator $hyphenator, CacheInterface $cache, WordDB $db)
    {
        $this->hyphenator = $hyphenator;
        $this->cache = $cache;
        $this->db = $db;
    }

    /**
     * @param string $word
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function hyphenate(string $word): string
    {

        $key = sha1($word);
        if (!($hyphenatedWord = $this->cache->get($key))) {
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $this->cache->set($key, $hyphenatedWord);
            $this->db->writeWordToDB($word);
            $this->db->writeHyphenatedWordToDB($word, $hyphenatedWord);
            //TODO write wordsandpatternsid's also
        }
        $this->db->writeWordToDB($word);
        $this->db->writeHyphenatedWordToDB($word, $hyphenatedWord);

        return $hyphenatedWord;
    }
}
