<?php

namespace Srdorado\SiigoClient\Model;

class Entity implements EntityInterface
{
    private $clientType;

    private $requestType;

    private $data;

    /**
     * @param string $clientType
     */
    public function __construct($clientType)
    {
        $this->clientType = $clientType;
        $this->data = [];
    }

    /**
     * @return string
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * @param string $clientType
     */
    public function setClientType($clientType)
    {
        $this->clientType = $clientType;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param string $key
     * @param float|array|bool|int|string $value
     * @return void
     */
    public function addKeyValue($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get key: value array and delete
     *
     * @param string $key
     * @param $value
     * @return array
     */
    public function getAndRemove($key)
    {
        $result = [];
        if (array_key_exists($key, $this->data)) {
            $result[$key] = $this->data[$key];
            unset($this->data[$key]);
        }
        return $result;
    }

    /**
     * @return int
     */
    public function countData()
    {
        return count($this->data);
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        return $this->requestType;
    }

    /**
     * @param string $requestType
     * @return void
     */
    public function setRequestType($requestType)
    {
        $this->requestType = $requestType;
    }
}
