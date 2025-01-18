<?php

namespace Srdorado\SiigoClient\Factory;

class ClientFactory extends AbstractFactory
{
    public function __construct()
    {
    }

    /**
     * @throws \ReflectionException
     */
    public function create($class = '', $params = [])
    {
        $class = new \ReflectionClass($class);

        if (empty($params)) {
            // Create a new Instance without arguments:
            $instance = $class->newInstance();
        } else {
            // Create a new Instance with arguments
            $instance = $class->newInstanceArgs($params);
        }

        return $instance;
    }
}
