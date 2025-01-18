<?php

namespace Srdorado\SiigoClient\Model\Client;

use GuzzleHttp\Exception\GuzzleException;
use Srdorado\SiigoClient\Enum\ClientType;
use Srdorado\SiigoClient\Model\Entity;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Model\Validator\TokenValidator;

class ClientToken extends AbstractClient
{
    private $username;

    private $accessKey;

    private $startDate;

    private $endDate;

    private $expirationTime;

    private $tokenType;

    /**
     * Construct
     *
     * @param string $baseUrl
     */
    public function __construct($baseUrl = '')
    {
        $this->baseUrl = $baseUrl;
        $this->initGuzzleClient();
        $this->validator = new TokenValidator();
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * @param $accessKey
     */
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param string $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return int
     */
    public function getExpirationTime()
    {
        return $this->expirationTime;
    }

    /**
     * @param int $expirationTime
     */
    public function setExpirationTime($expirationTime)
    {
        $this->expirationTime = $expirationTime;
    }

    /**
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * @param string $tokenType
     */
    public function setTokenType($tokenType)
    {
        $this->tokenType = $tokenType;
    }

    /**
     * @throws GuzzleException
     */
    public function getToken($entity = null)
    {
        if (!$entity) {
            $data = [
                'username' => $this->username ?? '',
                'access_key' => $this->accessKey ?? ''
            ];
            $entity = new Entity(ClientType::TOKEN);
            $entity->setData($data);
        }

        $this->validator->validate(\Srdorado\SiigoClient\Enum\EndPoint\Token::AUTH, $entity);

        $body = $this->validator->getBody(\Srdorado\SiigoClient\Enum\EndPoint\Token::AUTH, $entity);

        $headers = $this->getHeaders();

        $urlRequest = $this->getRequestUrl(\Srdorado\SiigoClient\Enum\EndPoint\Token::AUTH);

        $result = $this->post($urlRequest, $headers, json_encode($body));

        if ($result['code'] === 200) {
            $body = json_decode($result['contents'], true);
            $this->accessToken = $body['access_token'];
            $this->expirationTime = $body['expires_in'];
            $this->tokenType = $body['token_type'];
            $this->scope = $body['scope'];
        } else {
            $message =  'reponse - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }

        return $this->accessToken ? $this->accessToken : '';
    }

    public function getHeaders($params = [])
    {
        return \Srdorado\SiigoClient\Enum\EndPoint\Token::HEADER_POST;
    }
}
