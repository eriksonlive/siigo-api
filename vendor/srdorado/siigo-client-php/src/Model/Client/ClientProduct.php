<?php

namespace Srdorado\SiigoClient\Model\Client;

use Srdorado\SiigoClient\Exception\Rule\BadRequest;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Model\Validator\AbstractValidator;
use Srdorado\SiigoClient\Model\Validator\ProductValidator;

class ClientProduct extends AbstractClient
{
    /**
     * Construct
     *
     * @param string $baseUrl
     */
    public function __construct($baseUrl = '')
    {
        $this->baseUrl = $baseUrl;
        $this->initGuzzleClient();
        $this->validator = new ProductValidator();
    }

    /**
     * @param array $params
     * @return array
     */
    public function getHeaders($params = [])
    {
        $headers = \Srdorado\SiigoClient\Enum\EndPoint\Customer::HEADER_POST;
        $headers['Authorization'] = $params['access_token'];
        $headers['Partner-Id'] = $params['scope'];
        return $headers;
    }

    /**
     * Create product in siigo
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     */
    public function create($entity = null)
    {
        return $this->getBodyGeneric(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::CREATE,
            $entity
        );
    }

    /**
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getAll($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::GET_ALL,
            $entity,
            $allResponse
        );
    }

    /**
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getByCreatedStart($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::GET_BY_CREATED_START,
            $entity,
            $allResponse
        );
    }

    /**
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getByUpdatedStart($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::GET_BY_UPDATED_START,
            $entity,
            $allResponse
        );
    }

    /**
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getByCreatedEnd($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::GET_BY_CREATED_END,
            $entity,
            $allResponse
        );
    }

    /**
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getByUpdatedEnd($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::GET_BY_UPDATED_END,
            $entity,
            $allResponse
        );
    }

    /**
     * Get product by id
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getById($entity = null)
    {
        return $this->getUrlGenericList(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::GET_BY_ID,
            $entity
        );
    }

    /**
     * Get product by code
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getByCode($entity = null)
    {
        $product = [];
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Product::GET_BY_CODE, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Product::GET_BY_CODE, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->get($urlRequest, $headers);
        if ($result['code'] === 200) {
            $body = json_decode($result['contents'], true);
            $products = $body['results'];
            if (count($products) > 0) {
                $product = $products[0];
            }
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $product;
    }

    /**
     * Update product
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function update($entity = null)
    {
        //set token before send request
        $response = '';
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Product::UPDATE . 'U', $entity);
        $id = $entity->getAndRemove(AbstractValidator::URL_REQUEST);
        $body = $this->validator->getBody(\Srdorado\SiigoClient\Enum\EndPoint\Product::UPDATE, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $entity->setData($id[AbstractValidator::URL_REQUEST]);
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Product::UPDATE, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->put($urlRequest, $headers, json_encode($body));
        if ($result['code'] === 200) {
            $body = json_decode($result['contents'], true);
            $response = $body;
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $response;
    }

    /**
     * Delete product
     *
     * @param EntityInterface|null $entity
     * @return bool
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function delete($entity = null)
    {
        $result = false;
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Product::DELETE . 'D', $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Product::DELETE, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->del($urlRequest, $headers);
        if ($result['code'] === 200) {
            $body = json_decode($result['contents'], true);
            $result = $body['deleted'];
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $result;
    }

    /**
     * Get account groups
     *
     * @return array
     * @throws BadRequest|UrlRuleRequestException
     */
    public function getAccountGroups()
    {
        return $this->getUrlGenericList(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::ACCOUNT_GROUPS
        );
    }

    /**
     * Get WareHouses
     *
     * @return array
     * @throws BadRequest|UrlRuleRequestException
     */
    public function getWareHouses()
    {
        return $this->getUrlGenericList(
            \Srdorado\SiigoClient\Enum\EndPoint\Product::WAREHOUSES
        );
    }
}
