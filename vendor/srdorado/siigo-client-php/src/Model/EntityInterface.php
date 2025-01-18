<?php

namespace Srdorado\SiigoClient\Model;

interface EntityInterface
{
    /**
     * @return string
     */
    public function getClientType();

    /**
     * @param string $clientType
     */
    public function setClientType($clientType);

    /**
     * @return string
     */
    public function getRequestType();

    /**
     * @param string $requestType
     */
    public function setRequestType($requestType);

    /**
     * @return array
     */
    public function getData();

    /**
     * @param array $data
     */
    public function setData($data);


    /**
     * @return int
     */
    public function countData();

    /**
     * @param string $key
     * @param float|array|bool|int|string $value
     * @return void
     */
    public function addKeyValue($key, $value);
}
