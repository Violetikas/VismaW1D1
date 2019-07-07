<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 14:29
 */
declare(strict_types=1);

namespace Fikusas;


class UserInteraction
{

    /**
     * @return InputParameters
     */
    public function getUserInput(): InputParameters
    {
        global $argv;

        if (count($argv) < 2) {

            echo "Use commands:\n $argv[0] -w [userInput]\n $argv[0] -s [sentence]\n";
        } else {
            if (isset($argv[1]) && isset($argv[2])) {
                $userOption = $argv[1];
                $userInput = $argv[2];
                return new InputParameters($userOption, $userInput);
            }
        }
    }

    /**
     * @param string $result
     */

    //TODO this has to be done in hyphenation class and only result printed here
    public function printResult(string $result): void
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
        echo $result . "\n";
    }
}