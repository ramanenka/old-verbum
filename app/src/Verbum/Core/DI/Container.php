<?php

namespace Verbum\Core\DI;

class Container
{

    /**
     * Container of objects in format [alias => object, ...]
     *
     * @var array
     */
    protected $instances = [];

    /**
     * Injections of class methods in format [class name => [method name => injection, ...], ...]
     *
     * @var array
     */
    protected $classInjections = [];

    /**
     * Used to prevent a closure of a class on itself
     *
     * @var array
     */
    protected $instantiateChain = [];

    /**
     * Add instance to container
     *
     * @param string $name Alias for object in the container
     * @param $instance
     * @throws Exception
     */
    public function set($name, $instance)
    {
        if (!is_object($instance)) {
            throw new Exception(sprintf('Only object can be set to DI container. %s is given', gettype($instance)));
        }

        $this->instances[$name] = $instance;
    }

    /**
     * Retrieve an instance from the container by key
     *
     * @param $name
     * @return mixed
     * @throws Exception
     */
    public function get($name)
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        return $this->instantiate($name);
    }

    /**
     * Create an object with dependencies by class name
     *
     * @param $className
     * @return mixed
     * @throws Exception
     */
    protected function instantiate($className)
    {
        if (isset($this->instantiateChain[$className])) {
            $chain = array_keys($this->instantiateChain);
            $this->instantiateChain = [];
            throw new Exception(
                sprintf(
                    'Cannot instantiate %s. It depends on itself. Chain: %s',
                    $chain[0],
                    implode('->', $chain)
                )
            );
        }
        $this->instantiateChain[$className] = true;

        $instance = $this->createObject($className);

        $classInjections = $this->getClassInjections($className);
        if (!empty($classInjections)) {
            foreach ($classInjections as $methodName => $injection) {
                $instance->$methodName($this->get($injection));
            }
        }

        // last object in chain was just initialized, remove it from chain
        unset($this->instantiateChain[$className]);

        return $instance;
    }

    /**
     * Return a new instance of the class
     *
     * @param $className
     * @return mixed
     */
    protected function createObject($className)
    {
        return new $className();
    }

    /**
     * Return dependency injections for class methods
     *
     * @param $className
     * @return array
     */
    protected function getClassInjections($className)
    {
        if (!isset($this->classInjections[$className])) {
            $this->classInjections[$className] = $this->buildClassInjections($className);
        }

        return $this->classInjections[$className];
    }

    /**
     * Prepare and return dependency injections for class methods using annotations
     *
     * @param $className
     * @return array
     * @throws Exception
     */
    protected function buildClassInjections($className)
    {
        $reflection = new \ReflectionClass($className);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methodsInjection = [];
        foreach ($methods as $method) {
            if (substr($method->getName(), 0, 3) == 'set') {
                $docComment = $method->getDocComment();
                if (strpos($docComment, '@inject') === false) {
                    continue;
                }
                $methodParams = $method->getParameters();
                if (count($methodParams) != 1) {
                    throw new Exception(
                        sprintf(
                            'Setter %s in %s must have exactly one parameter',
                            $method->getName(),
                            $reflection->getName()
                        )
                    );
                }
                $methodsInjection[$method->getName()] = $this->retrieveInjectionFromComment($docComment);
            }
        }

        return $methodsInjection;
    }

    /**
     * Find and return an injection in the annotation
     *
     * @param $comment
     * @return mixed
     * @throws Exception
     */
    protected function retrieveInjectionFromComment($comment)
    {
        preg_match('/@inject\s+([A-Za-z_0-9\\\]+)\s?/', $comment, $result);

        if (!isset($result[1])) {
            throw new Exception('@inject annotation has wrong format');
        }

        return $result[1];
    }
}
