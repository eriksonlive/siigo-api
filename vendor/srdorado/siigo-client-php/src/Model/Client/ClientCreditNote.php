<?php

namespace Srdorado\SiigoClient\Model\Client;

use Srdorado\SiigoClient\Exception\Rule\BadRequest;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Model\Validator\CreditNoteValidator;

class ClientCreditNote extends AbstractClient
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
        $this->validator = new CreditNoteValidator();
    }

    public function getHeaders($params = [])
    {
        $headers = \Srdorado\SiigoClient\Enum\EndPoint\Customer::HEADER_POST;
        $headers['Authorization'] = $params['access_token'];
        $headers['Partner-Id'] = $params['scope'];
        return $headers;
    }

    /**
     * Create credit note in siigo
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     */
    public function create($entity = null)
    {
        return $this->getBodyGeneric(
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::CREATE,
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
            \Srdorado\SiigoClient\Enum\EndPoint\Voucher::GET_ALL,
            $entity,
            $allResponse
        );
    }

    /**
     * Get credit note by id
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getById($entity = null)
    {
        $response = '';
        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_ID, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl(\Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_ID, $entity);
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
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getByCreatedStart($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_CREATED_START,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_UPDATED_START,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_DATE_START,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_DATE_END,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_CREATED_END,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_UPDATED_END,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_CUSTOMER_BRANCH_OFFICE,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_CUSTOMER_IDENTIFICATION,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::GET_BY_DOCUMENT_ID,
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
            \Srdorado\SiigoClient\Enum\EndPoint\CreditNote::PDF,
            $entity
        );
    }
}
