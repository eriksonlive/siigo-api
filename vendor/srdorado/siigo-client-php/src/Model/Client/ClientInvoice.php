<?php

namespace Srdorado\SiigoClient\Model\Client;

use Srdorado\SiigoClient\Exception\Rule\BadRequest;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Model\Validator\AbstractValidator;
use Srdorado\SiigoClient\Model\Validator\InvoiceValidator;

class ClientInvoice extends AbstractClient
{
    protected $idempotency = null;

    /**
     * Construct
     *
     * @param string $baseUrl
     */
    public function __construct($baseUrl = '')
    {
        $this->baseUrl = $baseUrl;
        $this->initGuzzleClient();
        $this->validator = new InvoiceValidator();
    }

    public function getHeaders($params = [])
    {
        $headers = \Srdorado\SiigoClient\Enum\EndPoint\Customer::HEADER_POST;
        $headers['Authorization'] = $params['access_token'];
        $headers['Partner-Id'] = $params['scope'];
        if (array_key_exists('idempotency_key', $params)) {
            $headers['Idempotency-Key'] = $params['idempotency_key'];
        }
        return $headers;
    }

    /**
     * @inheritdoc
     * @throws UrlRuleRequestException
     */
    protected function getBodyGeneric($endPoint, $entity = null)
    {
        // INVOICE Validator Se comenta esta linea ya que causa problemas al crear un invoice
        // $this->validator->validate($endPoint, $entity);
        $headersData =  [
            'access_token' => $this->accessToken,
            'scope' => $this->scope
        ];
        if ($this->idempotency) {
            $headersData['idempotency_key'] = $this->idempotency;
        }
        $headers = $this->getHeaders($headersData);
        $body = $this->validator->getBody($endPoint, $entity);
        $urlRequest = $this->getRequestUrl($endPoint);
        $result = $this->post($urlRequest, $headers, json_encode($body));
        if ($result['code'] === 201) {
            $result = json_decode($result['contents'], true);
            $response = $result;
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $response;
    }

    /**
     * Create invoice in siigo
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest|UrlRuleRequestException
     */
    public function create($entity = null, $idempotency = null)
    {
        $this->idempotency = $idempotency;
        return $this->getBodyGeneric(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::CREATE,
            $entity
        );
    }

    /**
     * Update invoice
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function update($entity = null)
    {
        $response = '';
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::UPDATE . 'U', $entity);
        $id = $entity->getAndRemove(AbstractValidator::URL_REQUEST);
        $body = $this->validator->getBody(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::UPDATE, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $entity->setData($id[AbstractValidator::URL_REQUEST]);
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::UPDATE, $entity);
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
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getAll($entity = null, $allResponse = false)
    {
        return $this->getListRequest(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_ALL, $entity, $allResponse);
    }

    /**
     * Get invoice by id
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getById($entity = null)
    {
        $response = '';
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET . 'G', $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->get($urlRequest, $headers);
        if ($result['code'] === 200) {
            $result = json_decode($result['contents'], true);
            $response = $result;
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $response;
    }

    /**
     * Delete invoice
     *
     * @param EntityInterface|null $entity
     * @return bool
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function delete($entity = null)
    {
        $result = false;
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::DELETE . 'D', $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::DELETE, $entity);
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

    /**
     * Annul invoice
     *
     * @param EntityInterface|null $entity
     * @return bool
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function annul($entity = null)
    {
        $result = false;
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::ANNUL, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::ANNUL, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->post($urlRequest, $headers, '');
        if ($result['code'] === 200) {
            $body = json_decode($result['contents'], true);
            $result = $body['Annul'];
        } else {
            $message =  'reponse - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $result;
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
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_CREATED_START,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_UPDATED_START,
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
    public function getByDateStart($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_DATE_START,
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
    public function getByDateEnd($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_DATE_END,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_CREATED_END,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_UPDATED_END,
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
    public function getByCustomerBranchOffice($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_CUSTOMER_BRANCH_OFFICE,
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
    public function getByName($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_NAME,
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
    public function getByCustomerIdentification($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_CUSTOMER_IDENTIFICATION,
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
    public function getByDocumentId($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::GET_BY_DOCUMENT_ID,
            $entity,
            $allResponse
        );
    }

    /**
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getPDF($entity = null)
    {
        return $this->getUrlGenericList(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::PDF,
            $entity
        );
    }

    /**
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getStampErrors($entity = null)
    {
        return $this->getUrlGenericList(
            \Srdorado\SiigoClient\Enum\EndPoint\Invoice::STAMP_ERRORS,
            $entity
        );
    }

    /**
     * @param EntityInterface|null $entity
     * @return array
     */
    public function sendEmailsCopy($entity = null)
    {
        //set token before send request
        $response = [];
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::MAIL, $entity);
        $id = $entity->getAndRemove(AbstractValidator::URL_REQUEST);
        $body = $this->validator->getBody(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::MAIL, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $entity->setData($id[AbstractValidator::URL_REQUEST]);
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\Invoice::MAIL, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->post($urlRequest, $headers, json_encode($body));
        if ($result['code'] === 201) {
            $body = json_decode($result['contents'], true);
            $response = $body;
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $response;
    }
}
