<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-04
 * Time: 14:18
 */

declare(strict_types=1);

namespace Fikusas;


class Hyphenate
{
    private $values2;

    /**
     * Hyphenate constructor.
     * @param $values1
     */
    public function __construct($values1)
    {
        $this->values2 = $values1;
    }

    public function hyphenate(string $word): string
    {
        $time_start = microtime(true);
        $numbersInWord = [];

        foreach ($this->values2 as $value) {
            //removes decimal numbers and dots
            $toFind = preg_replace('/[\d.]/', '', $value);

            //finds if there's needle in haystack
            $position = strpos($word, $toFind);

            if (false === $position) {
                continue;
            }
            //finds if there's dot in the beginning of needle and only searches the beginning of haystack
            if ($value[0] == '.' && $position !== 0) {
                continue;
            }
            //finds if there's dot at the end
            if (($value[strlen($value) - 1] === '.') && ($position !== (strlen($word) - strlen($toFind)))) {
                continue;
            }

            // finds is there's no number in position or if it's smaller than number, if so - puts number in position

            $numbers = $this->extractNumbers($value);
            foreach ($numbers as $position1 => $number) {
                $position1 = $position1 + $position;
                if (isset($numbersInWord[$position1]) !== true || $numbersInWord[$position1] < $number) {
                    $numbersInWord[$position1] = $number;
                }
            }
        }
        //splits word into array and insert numbers into certain places if number is detected in the place
        $final = '';
        foreach (str_split($word) as $i => $l) {
            $final .= $l;
            if (isset($numbersInWord[$i])) {
                $final .= $numbersInWord[$i];
            }
        }
        $time_end = microtime(true);
        $time = $time_end - $time_start;
        echo "script took $time seconds to execute\n";
        return $final;
    }


    private function extractNumbers(string $argument): array
//finds if there's a number in needle, finds it's position
    {
        $result = [];
        if (preg_match_all('/\d+/', $argument, $matches, PREG_OFFSET_CAPTURE) > 0) {
            $offset = preg_match('/[^\d.]/', $argument);
            foreach ($matches[0] as $match) {
                [$number, $position] = $match;
                $position = $position - $offset;
                $offset = $offset + strlen($number);
                $result[$position] = (int)$number;
            }
        }

        return $result;
    }

}