<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-07
 * Time: 15:35
 */

namespace Fikusas;


class InputParameters
{
    private $userOption;
    private $userInput;

    /**
     * InputParameters constructor.
     * @param $userOption
     * @param $userInput
     */
    public function __construct($userOption, $userInput)
    {
        $this->userOption = $userOption;
        $this->userInput = $userInput;
    }

    /**
     * @return mixed
     */
    public function getUserOption()
    {
        return $this->userOption;
    }

    /**
     * @return mixed
     */
    public function getUserInput()
    {
        return $this->userInput;
    }
}