<?php


namespace Fikusas\Hyphenation;

use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;

class DBHyphenator implements WordHyphenatorInterface
{
    private $hyphenator;
    private $wdb;
    private $hdb;
    private $patterns;
    private $pdb;



    public function __construct(WordHyphenator $hyphenator, HyphenatedWordsDB $hdb, WordDB $wordsDB, PatternDB $pdb, WordHyphenator $patterns)
    {
        $this->hyphenator = $hyphenator;
        $this->wdb = $wordsDB;
        $this->hdb = $hdb;
        $this->pdb = $pdb;
        $this->patterns = $patterns;
    }

    public function hyphenate(string $word): string
    {
        $hyphenatedWord = $this->hdb->getFromDB($word);
        if (!$hyphenatedWord) {
            $this->wdb->writeToDB($word);
            $patterns = $this->patterns->findPatterns($word);
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $this->hdb->writeToDB($word, $hyphenatedWord);
            $this->wdb->writeWordsPatternsIDs($word,$patterns);


        }
        return $hyphenatedWord;
    }
}


