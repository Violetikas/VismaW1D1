<?php


namespace Fikusas\Config;
use Fikusas\Config\Error\ParameterNotFoundError;
class ArrayConfig implements ConfigInterface
{
    /** @var array */
    private $values;
    /**
     * ArrayConfig constructor.
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }
    public function getParameter(string $name)
    {
        if (!array_key_exists($name, $this->values)) {
            throw new ParameterNotFoundError($name);
        }
        return $this->values[$name];
    }
}
