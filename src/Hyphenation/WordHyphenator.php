<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-04
 * Time: 14:18
 */

declare(strict_types=1);

namespace Fikusas\Hyphenation;

use Fikusas\DB\DatabaseConnectorInterface;
use Fikusas\DB\WordDB;
use Fikusas\Patterns\PatternLoaderInterface;
use Psr\SimpleCache\CacheInterface;
use Fikusas\DB\DatabaseConnector;

/**
 * Class WordHyphenator
 * @package Fikusas\Hyphenation
 */
class WordHyphenator implements WordHyphenatorInterface
{

    private $syllables;
    private $wordDB;
    private $dbConfig;

    /**
     * WordHyphenator constructor.
     * @param PatternLoaderInterface $loader
     * @param DatabaseConnectorInterface $dbConfig
     */
    public function __construct(PatternLoaderInterface $loader, DatabaseConnectorInterface $dbConfig)
    {
        $this->syllables = $loader->loadPatterns();
        $this->dbConfig = $dbConfig;
        $this->wordDB = new WordDB($dbConfig);
    }

    /**
     * @param string $word
     * @return string
     */
    public function hyphenate(string $word): string
    {
        $numbersInWord = [];
        $syllables = [];

        foreach ($this->syllables as $syllable) {
            $toFind = preg_replace('/[\d.]/', '', $syllable);
            $position = strpos($word, $toFind);
            if (false === $position) {
                continue;
            }
            if ($syllable[0] == '.' && $position !== 0) {
                continue;
            }
            if (($syllable[strlen($syllable) - 1] === '.') && ($position !== (strlen($word) - strlen($toFind)))) {
                continue;
            }
            $syllables[] = $syllable;
            $numbers = $this->extractNumbers($syllable);
            foreach ($numbers as $position1 => $number) {
                $position1 = $position1 + $position;
                if (isset($numbersInWord[$position1]) !== true || $numbersInWord[$position1] < $number) {
                    $numbersInWord[$position1] = $number;
                }
            }
        }
        $final = '';
        foreach (str_split($word) as $i => $l) {
            $final .= $l;
            if (isset($numbersInWord[$i])) {
                $final .= $numbersInWord[$i];
            }
        }
        $this->wordDB->writeHyphenatedWordToDB($word, $this->printResult($final));
        $this->storeSyllables($word, $syllables);


        return $this->printResult($final);
    }

    /**
     * @param string $syllable
     * @return array
     */
    private function extractNumbers(string $syllable): array
    {
        $result = [];
        if (preg_match_all('/\d+/', $syllable, $matches, PREG_OFFSET_CAPTURE) > 0) {
            $offset = preg_match('/[^\d.]/', $syllable);
            foreach ($matches[0] as $match) {
                [$number, $position] = $match;
                $position = $position - $offset;
                $offset = $offset + strlen($number);
                $result[$position] = (int)$number;
            }
        }
        return $result;
    }

    /**
     * @param string $result
     * @param string $word
     * @return string
     */
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

    /**
     * @param string $word
     * @param array $syllables
     */
    private function storeSyllables(string $word, array $syllables)
    {
        $this->wordDB->storeWordsPatternsIDs($word, $syllables);
    }

}

