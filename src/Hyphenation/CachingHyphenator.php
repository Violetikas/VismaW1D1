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
    private $wordDB;
    private $hyphenatedWordsDB;
    private $patternDB;
    private $patterns;

    public function __construct(WordHyphenatorInterface $hyphenator, CacheInterface $cache, WordDB $wordDB, HyphenatedWordsDB $hyphenatedWordsDB, PatternDB $patternDB, WordHyphenator $patterns)
    {
        $this->hyphenator = $hyphenator;
        $this->cache = $cache;
        $this->wordDB = $wordDB;
        $this->hyphenatedWordsDB = $hyphenatedWordsDB;
        $this->patternDB = $patternDB;
        $this->patterns = $patterns;
    }

    public function hyphenate(string $word): string
    {

        $key = sha1($word);
        if (!($hyphenatedWord = $this->cache->get($key))) {
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $this->cache->set($key, $hyphenatedWord);
            $patterns = $this->patterns->findPatterns($word);
            $this->wordDB->writeToDB($word);
            $this->hyphenatedWordsDB->writeToDB($word, $hyphenatedWord);
            $this->wordDB->writeWordsPatternsIDs($word,$patterns);

        }
        $this->wordDB->writeToDB($word);
        $this->hyphenatedWordsDB->writeToDB($word, $hyphenatedWord);
        $patterns = $this->patterns->findPatterns($word);
        $this->wordDB->writeWordsPatternsIDs($word,$patterns);

        return $hyphenatedWord;
    }
}
