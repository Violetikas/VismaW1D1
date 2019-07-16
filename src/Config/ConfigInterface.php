<?php


namespace Fikusas\Config;


use Fikusas\Config\Error\ParameterNotFoundError;

interface ConfigInterface
{
    /**
     * Get parameter value.
     *
     * @param string $name
     * @return mixed
     * @throws ParameterNotFoundError
     */
    public function getParameter(string $name);
}
