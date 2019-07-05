<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-05
 * Time: 11:04
 */
declare(strict_types=1);

namespace Fikusas;
//use \Fikusas\Hyphenate;
//use \Fikusas\EmailValidator;
//use \Fikusas\SentencePrepare;

class OptionDivider
{

    public function divideOptions(string $userOption, string $userInput): void
    {

        if (isset($userOption) && isset($userInput)) {

            if ($userOption = '-w') {
                //new
                //TODO use hyphenate algorithm
                echo 'do something';
            }
            if ($userOption = '-s') {

                //TODO create sentence algorithm
                echo 'do something';
            }
            if ($userOption = '-email') {
                //TODO use email validation algorithm
                echo 'do something';
            }
        }


    }


}
