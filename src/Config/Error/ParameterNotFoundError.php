<?php

namespace Fikusas\Config\Error;

use Exception;

class ParameterNotFoundError extends Exception
{
    /** @var string */
    private $name;

    /**
     * ParameterNotFoundError constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        parent::__construct("Parameter '$name' not found!");
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
