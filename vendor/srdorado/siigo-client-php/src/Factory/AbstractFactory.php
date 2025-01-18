<?php

namespace Srdorado\SiigoClient\Factory;

abstract class AbstractFactory
{
    abstract public function create($class = '', $params = []);
}
