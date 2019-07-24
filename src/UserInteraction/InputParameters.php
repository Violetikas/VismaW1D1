<?php
/**
 * Created by PhpStorm.
 * User: violetatamasauskiene
 * Date: 2019-07-07
 * Time: 15:35
 */

namespace Fikusas\UserInteraction;


class InputParameters
{
    private $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption(string $name)
    {
        return $this->options[$name]??null;
    }
}
