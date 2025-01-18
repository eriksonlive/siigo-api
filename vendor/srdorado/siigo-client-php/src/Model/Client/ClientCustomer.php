<?php

namespace Srdorado\SiigoClient\Model\Client;

use Srdorado\SiigoClient\Exception\Rule\BadRequest;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Model\Validator\AbstractValidator;
use Srdorado\SiigoClient\Model\Validator\CustomerValidator;

class ClientCustomer extends AbstractClient
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
        $this->validator = new CustomerValidator();
    }

    /**
     * Get request headers
     *
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
     * Create customer in siigo
     *
     * @param EntityInterface|null $entity
     * @return array //Id de client siggo or ''
     * @throws BadRequest
     */
    public function create($entity = null)
    {
        return $this->getBodyGeneric(
            \Srdorado\SiigoClient\Enum\EndPoint\Customer::CREATE,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Customer::GET_ALL,
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
    public function getByBranchOffice($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Customer::GET_BY_BRANCH_OFFICE,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Customer::GET_BY_CREATED_START,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Customer::GET_BY_UPDATED_START,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Customer::GET_BY_CREATED_END,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Customer::GET_BY_UPDATED_END,
            $entity,
            $allResponse
        );
    }

    /**
     * Get customer by id
     *
     * @param EntityInterface|null $entity
     * @return string
     * @throws BadRequest
     */
    public function getById($entity = null)
    {
        return $this->getUrlGenericWithKey(
            'id',
            \Srdorado\SiigoClient\Enum\EndPoint\Customer::GET_BY_ID,
            $entity
        );
    }

    /**
     * Update customer
     *
     * @param EntityInterface|null $entity
     * @return string
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function update($entity = null)
    {
        //set token before send request
        $clientId = '';
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Customer::UPDATE . 'U', $entity);
        $id = $entity->getAndRemove(AbstractValidator::URL_REQUEST);
        $body = $this->validator->getBody(\Srdorado\SiigoClient\Enum\EndPoint\Customer::UPDATE, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $entity->setData($id[AbstractValidator::URL_REQUEST]);
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Customer::UPDATE, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->put($urlRequest, $headers, json_encode($body));
        if ($result['code'] === 200) {
            $body = json_decode($result['contents'], true);
            $clientId = $body['id'];
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $clientId;
    }

    /**
     * Delete customer
     *
     * @param EntityInterface|null $entity
     * @return bool
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function delete($entity = null)
    {
        $result = false;
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Customer::DELETE . 'D', $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Customer::DELETE, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->del($urlRequest, $headers);
        if ($result['code'] === 200) {
            $body = json_decode($result['contents'], true);
            $result = $body['deleted'];
        } else {
            $message =  'reponse - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $result;
    }
}
