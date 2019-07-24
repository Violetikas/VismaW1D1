<?php


namespace Fikusas\DI;


use Closure;
use Exception;
use ReflectionClass;

class Container
{
    /** @var array */
    private $instances = [];
    /** @var array */
    protected $definitions = [];
    /** @var array */
    private $aliases = [];
    /** @var array */
    private $arguments = [];

    /**
     * @param string $id
     *
     * @return mixed|null|object
     * @throws Exception
     */
    public function get(string $id)
    {
        // Check for alias
        $id = $this->aliases[$id] ?? $id;

        // Create instance if not yet created
        if (!isset($this->instances[$id])) {
            $this->instances[$id] = $this->resolve($id);
        }

        // Return instance
        return $this->instances[$id];
    }

    /**
     * Resolve instance
     *
     * @param $id
     *
     * @return mixed|object
     * @throws Exception
     */
    private function resolve(string $id)
    {
        $definition = $this->definitions[$id] ?? null;
        if ($definition instanceof Closure) {
            return $definition($this);
        }
        $reflector = new ReflectionClass($id);
        // check if class is instantiable
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$id} is not instantiable");
        }
        // get class constructor
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            // get new instance from class
            return $reflector->newInstance();
        }
        // get constructor params
        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters, $id);
        // get new instance with dependencies resolved
        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * get all dependencies resolved
     *
     * @param array $parameters
     * @param string $id
     *
     * @return array
     * @throws Exception
     */
    private function getDependencies(array $parameters, string $id)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            // get the type hinted class
            $dependency = $parameter->getClass();
            $name = $parameter->name;
            if ($value = $this->arguments[$id][$name] ?? null) {
                $dependencies[$name] = $this->get($value);
            } elseif ($dependency === NULL) {
                // check if default value for a parameter is available
                if ($parameter->isDefaultValueAvailable()) {
                    // get default value of parameter
                    $dependencies[$name] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Can not resolve argument \${$name} of type '{$parameter->getType()}' for {$id}");
                }
            } else {
                // get dependency resolved
                $dependencies[$name] = $this->get($dependency->name);
            }
        }
        return $dependencies;
    }

    /**
     * Set service alias
     *
     * @param string $id
     * @param string $aliasId
     */
    public function setAlias(string $id, string $aliasId): void
    {
        $this->aliases[$id] = $aliasId;
    }

    /**
     * @param string $id
     * @param $definition
     */
    public function setDefinition(string $id, $definition): void
    {
        $this->definitions[$id] = $definition;
    }

    /**
     * Override
     *
     * @param string $id
     * @param string $argument
     * @param string $otherId
     */
    public function setArgument(string $id, string $argument, string $otherId): void
    {
        $arguments = $this->arguments[$id] ?? [];
        $arguments[$argument] = $otherId;
        $this->arguments[$id] = $arguments;
    }
}
