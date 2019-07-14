<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 14:29
 */
declare(strict_types=1);

namespace Fikusas\UserInteraction;


class UserInteraction
{
    public function getUserInput($argv): ?InputParameters
    {
        if (count($argv) < 2) {

            echo "Use commands:\n $argv[0] -w [userInput]\n $argv[0] -s ['sentence']\n $argv[0] -f ['file path']\n";
            return null;
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

}



