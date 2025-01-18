<?php

namespace Srdorado\SiigoClient\Utils;

use Srdorado\SiigoClient\Model\EntityInterface;

class BodyFactory
{
    /**
     * @param EntityInterface $entity
     * @param array $rules
     * @return array
     */
    public function getBody($entity, $rules)
    {
        return $this->merge($rules, $entity->getData(), []);
    }

    /**
     * @param array $rules
     * @param array $data
     * @return array
     */
    private function merge($rules, $data)
    {
        $body = [];

        foreach ($rules as $key => $rule) {
            if (strpos($key, '*') !== false) {
                $tam = strlen($key);
                $key = substr($key, 1, $tam);
                if (!array_key_exists($key, $data)) {
                    continue;
                }
            }

            $keyData = $key;

            if (is_numeric($key)) {
                $keyData = intval($key);
            }

            //TODO: vaidate second level and array array

            $body[$key] = $data[$keyData];
        }

        return $body;
    }
}
