<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-04
 * Time: 14:18
 */

declare(strict_types=1);

namespace Fikusas\Hyphenation;
use Psr\SimpleCache\CacheInterface;

class WordHyphenator
{
    private $syllables;
    private $cache;

    /**
     * WordHyphenator constructor.
     * @param array $syllables
     * @param CacheInterface $cache
     */
    public function __construct(array $syllables, CacheInterface $cache)
    {
        $this->syllables = $syllables;
        $this->cache = $cache;
    }

    /**
     * @param string $word
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function hyphenate(string $word): string
    {
        $numbersInWord = [];


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
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    private function printResult(string $result): string
    {
        $key=sha1($result);
        if (!$this->cache->has($key)){
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
            $this->cache->set($key, $result);
            return $result;

        } else {
            return $this->cache->get($key, $result);
        }

    }
}

