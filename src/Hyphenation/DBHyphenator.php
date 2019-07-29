<?php


namespace Fikusas\Hyphenation;

use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;

class DBHyphenator implements WordHyphenatorInterface
{
    private $hyphenator;
    private $wordsDB;
    private $hyphenatedWordsDB;
    private $patterns;
    private $patternDB;



    public function __construct(WordHyphenator $hyphenator, HyphenatedWordsDB $hyphenatedWordsDB, WordDB $wordsDB, PatternDB $patternDB, WordHyphenator $patterns)
    {
        $this->hyphenator = $hyphenator;
        $this->wordsDB = $wordsDB;
        $this->hyphenatedWordsDB = $hyphenatedWordsDB;
        $this->patternDB = $patternDB;
        $this->patterns = $patterns;
    }

    public function hyphenate(string $word): string
    {
        $hyphenatedWord = $this->hyphenatedWordsDB->getFromDB($word);
        if (!$hyphenatedWord) {
            $this->wordsDB->writeToDB($word);
            $patterns = $this->patterns->findPatterns($word);
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $this->hyphenatedWordsDB->writeToDB($word, $hyphenatedWord);
            $this->wordsDB->writeWordsPatternsIDs($word,$patterns);


        }
        return $hyphenatedWord;
    }
}


