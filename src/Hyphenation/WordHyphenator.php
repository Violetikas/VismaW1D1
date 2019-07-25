<?php

declare(strict_types=1);

namespace Fikusas\Hyphenation;

use Fikusas\DB\DatabaseConnectorInterface;
use Fikusas\DB\HyphenatedWordsDB;
use Fikusas\DB\PatternDB;
use Fikusas\DB\WordDB;
use Fikusas\Patterns\PatternLoaderInterface;

class WordHyphenator implements WordHyphenatorInterface
{

    private $patterns;
    private $wordDB;
    private $dbConfig;
    private $patternDB;
    private $hyphenatedWordsDB;

    /**
     * WordHyphenator constructor.
     * @param PatternLoaderInterface $loader
     * @param DatabaseConnectorInterface $dbConfig
     * @param WordDB $wordDB
     * @param PatternDB $patternDB
     * @param HyphenatedWordsDB $hyphenatedWordsDB
     */
    public function __construct(PatternLoaderInterface $loader, DatabaseConnectorInterface $dbConfig, WordDB $wordDB, PatternDB $patternDB, HyphenatedWordsDB $hyphenatedWordsDB)
    {
        $this->patterns = $loader->loadPatterns();
        $this->dbConfig = $dbConfig;
        $this->wordDB = $wordDB;
        $this->patternDB = $patternDB;
        $this->hyphenatedWordsDB = $hyphenatedWordsDB;
    }

    public function hyphenate(string $word): string
    {
        var_dump('word');
        $this->wordDB->writeToDB($word);
        $numbersInWord = $this->findNumbersInWord($word);

        $final = '';
        foreach (str_split($word) as $i => $l) {
            $final .= $l;
            if (isset($numbersInWord[$i])) {
                $final .= $numbersInWord[$i];
            }
        }
        $this->wordDB->writeToDB($word);
        $this->hyphenatedWordsDB->writeToDB($word, $this->printResult($final));

        return $this->printResult($final);
    }

    private function findNumbersInWord(string $word): array
    {

        $numbersInWord = [];
        $patterns = [];
        foreach ($this->patterns as $pattern) {
            $toFind = preg_replace('/[\d.]/', '', $pattern);
            $position = strpos($word, $toFind);
            if (false === $position) {
                continue;
            }
            if ($pattern[0] == '.' && $position !== 0) {
                continue;
            }
            if (($pattern[strlen($pattern) - 1] === '.') && ($position !== (strlen($word) - strlen($toFind)))) {
                continue;
            }
            $patterns[] = $pattern;
            $numbers = $this->extractNumbers($pattern);
            foreach ($numbers as $position1 => $number) {
                $position1 = $position1 + $position;
                if (isset($numbersInWord[$position1]) !== true || $numbersInWord[$position1] < $number) {
                    $numbersInWord[$position1] = $number;
                }
            }
        }
        $this->storePatterns($word, $patterns);
        return $numbersInWord;
    }

    public function findPatterns(string $word): array
    {
        $patterns = [];
        foreach ($this->patterns as $pattern) {
            $toFind = preg_replace('/[\d.]/', '', $pattern);
            $position = strpos($word, $toFind);
            if (false === $position) {
                continue;
            }
            if ($pattern[0] == '.' && $position !== 0) {
                continue;
            }
            if (($pattern[strlen($pattern) - 1] === '.') && ($position !== (strlen($word) - strlen($toFind)))) {
                continue;
            }
            $patterns[] = $pattern;
        }
        return $patterns;
    }


    private function extractNumbers(string $pattern): array
    {
        $result = [];
        if (preg_match_all('/\d+/', $pattern, $matches, PREG_OFFSET_CAPTURE) > 0) {
            $offset = preg_match('/[^\d.]/', $pattern);
            foreach ($matches[0] as $match) {
                [$number, $position] = $match;
                $position = $position - $offset;
                $offset = $offset + strlen($number);
                $result[$position] = (int)$number;
            }
        }
        return $result;
    }

    private function printResult(string $result): string
    {
        for ($i = 0; $i < strlen($result); $i++) {
            if (!is_numeric($result[$i])) {
                continue;
            }
            if (((int)$result[$i]) % 2 !== 0) {
                $result = str_replace($result[$i], '-', $result);
            } else {
                $result = str_replace($result[$i], '', $result);
            }
        }
        return $result;
    }


    private function storePatterns(string $word, array $patterns)
    {
        $this->wordDB->writeWordsPatternsIDs($word, $patterns);
    }
}

