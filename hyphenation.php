<?php


function hyphenate(string $word, array $values): array
{
    $time_start = microtime(true);
    $newArray = array();
    $numbersInWord = [];

    foreach ($values as $value) {
        //removes decimal numbers and dots
        $toFind = preg_replace('/[\d.]/', '', $value);

        //finds if there's needle in haystack

        $position = strpos($word, $toFind);
        if (false === $position) {
            continue;
        }
        //finds if there's dot in the beginning of needle and only searches the beginning of haystack
        if ($value[0] == '.') {
            if ($position !== 0) {
                continue;
            }
        }
        //finds if there's dot in the end of the needle and only searches the end of haystack
        elseif ($value[strlen($value) - 1] == '.') {
            if ($position !== (strlen($word) - strlen($toFind) - 1)) {
                continue;
            }
        }

        //
        $numbers = extractNumbers($value);

        foreach ($numbers as $position1 => $number) {
            $position1 = $position1 + $position;
            // finds is there's no number in position or if it's smaller than number, if so - puts number in position
            if (isset($numbersInWord[$position1]) !== true || $numbersInWord[$position1] < $number) {
                $numbersInWord[$position1] = $number;
            }
        }
        //create new array to put all letters and numbers into one by offsets
    }
    var_dump($word, $numbersInWord);





    $time_end = microtime(true);
    $time = $time_end - $time_start;

    echo "script took $time seconds to execute";

    return $newArray;

}

function extractNumbers(string $argument): array
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














