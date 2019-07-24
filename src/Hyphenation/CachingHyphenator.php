<?php


namespace Fikusas\Hyphenation;

use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Psr\SimpleCache\CacheInterface;
use Fikusas\DB\WordDB;

class CachingHyphenator implements WordHyphenatorInterface
{
    private $hyphenator;
    private $cache;
    private $wdb;
    private $hdb;
    private $pdb;
    private $patterns;


    public function __construct(WordHyphenatorInterface $hyphenator, CacheInterface $cache, WordDB $db, HyphenatedWordsDB $hdb, PatternDB $pdb, WordHyphenator $patterns)
    {
        $this->hyphenator = $hyphenator;
        $this->cache = $cache;
        $this->wdb = $db;
        $this->hdb = $hdb;
        $this->pdb = $pdb;
        $this->patterns = $patterns;
    }

    public function hyphenate(string $word): string
    {
        $key = sha1($word);
        if (!($hyphenatedWord = $this->cache->get($key))) {
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $this->cache->set($key, $hyphenatedWord);
            $patterns = $this->patterns->findPatterns($word);
            $this->wdb->writeToDB($word);
            $this->hdb->writeToDB($word, $hyphenatedWord);
            $this->wdb->writeWordsPatternsIDs($word,$patterns);

        }
        $this->wdb->writeToDB($word);
        $this->hdb->writeToDB($word, $hyphenatedWord);

        return $hyphenatedWord;
    }
}
