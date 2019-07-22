<?php


namespace Fikusas\Hyphenation;


use Fikusas\DB\DatabaseConnector;
use Fikusas\DB\WordDB;
use Psr\SimpleCache\CacheInterface;


/**
 * Class DBHyphenator
 * @package Fikusas\Hyphenation
 */
class DBHyphenator implements WordHyphenatorInterface
{
    /**
     * @var WordHyphenator
     */
    private $hyphenator;

    /**
     * @var WordDB
     */
    private $db;


    /**
     * DBHyphenator constructor.
     * @param WordHyphenator $hyphenator
     * @param WordDB $db
     */
    public function __construct(WordHyphenator $hyphenator, WordDB $db)
    {
        $this->hyphenator = $hyphenator;
        $this->db = $db;
    }


    /**
     * @param string $word
     * @return string
     */
    public function hyphenate(string $word): string
    {
        $hyphenatedWord = $this->db->getHyphenatedWordFromDB($word);
        if (!$hyphenatedWord) {
            $this->db->writeWordToDB($word);
            $hyphenatedWord = $this->hyphenator->hyphenate($word);
            $this->db->writeHyphenatedWordToDB($word, $hyphenatedWord);
        }
        return $hyphenatedWord;
    }
}


