<?php

namespace Srdorado\SiigoClient\Model\Client;

use Srdorado\SiigoClient\Enum\Rule\Generic;
use Srdorado\SiigoClient\Exception\Rule\BadRequest;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Model\Validator\GenericValidator;

class ClientGeneric extends AbstractClient
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
        $this->validator = new GenericValidator();
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
     * Get taxes
     *
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getTaxes()
    {
        return $this->getUrlGenericList(\Srdorado\SiigoClient\Enum\EndPoint\Generic::TAXES);
    }

    /**
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getDocumentTypes(EntityInterface $entity = null)
    {
        return $this->getUrlGenericList(\Srdorado\SiigoClient\Enum\EndPoint\Generic::GET_DOCUMENT_TYPES, $entity);
    }

    /**
     * Get price list
     *
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getPriceList()
    {
        return $this->getUrlGenericList(\Srdorado\SiigoClient\Enum\EndPoint\Generic::GET_PRICE_LISTS);
    }

    /**
     * Get users
     *
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getUsers($entity = null, $allResponse = false)
    {
        return $this->getListRequest(
            \Srdorado\SiigoClient\Enum\EndPoint\Generic::GET_USERS,
            $entity,
            $allResponse
        );
    }


    /**
     * Get payment types
     *
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getPaymentTypes(EntityInterface $entity = null)
    {
        return $this->getUrlGenericList(\Srdorado\SiigoClient\Enum\EndPoint\Generic::GET_PAYMENT_TYPES, $entity);
    }

    /**
     * Get cost centers
     *
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getCostCenters()
    {
        return $this->getUrlGenericList(\Srdorado\SiigoClient\Enum\EndPoint\Generic::GET_COST_CENTERS);
    }

    /**
     * Get fixed assets centers
     *
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getFixedAssets()
    {
        return $this->getUrlGenericList(\Srdorado\SiigoClient\Enum\EndPoint\Generic::GET_FIXED_ASSETS);
    }
}
