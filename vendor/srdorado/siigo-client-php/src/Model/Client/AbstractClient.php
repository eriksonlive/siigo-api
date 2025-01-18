<?php

namespace Srdorado\SiigoClient\Model\Client;

use Srdorado\SiigoClient\Exception\Rule\BadRequest;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use GuzzleHttp\HandlerStack;
use Spatie\GuzzleRateLimiterMiddleware\RateLimiterMiddleware;

abstract class AbstractClient
{
    protected $baseUrl;

    protected $client;

    protected $validator;

    protected $accessToken;

    protected $scope;

    /**
     * Init GuzzleClient and set limit request per minute
     *
     * @return void
     */
    protected function initGuzzleClient()
    {
        $stack = HandlerStack::create();
        $stack->push(RateLimiterMiddleware::perMinute(100));

        $this->client = new \GuzzleHttp\Client(
            [
                'handler' => $stack,
                'curl' => [
                    CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param $accessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get scope
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Set scope
     *
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @param string $endPoint
     * @return string
     */
    public function getRequestUrl($endPoint)
    {
        return $this->baseUrl . $endPoint;
    }

    /**
     * @return array
     */
    protected function post($urlRequest, $headers, $body)
    {
        $request = new \GuzzleHttp\Psr7\Request('POST', $urlRequest, $headers, $body);
        $result = $this->client->sendAsync($request)->wait();
        return [
            'code' => $result->getStatusCode(),
            'contents' => (string)$result->getBody()
        ];
    }

    protected function get($urlRequest, $headers, $body = '')
    {
        $request = new \GuzzleHttp\Psr7\Request('GET', $urlRequest, $headers, $body);
        $result = $this->client->sendAsync($request)->wait();
        return [
            'code' => $result->getStatusCode(),
            'contents' => (string)$result->getBody()
        ];
    }

    /**
     * @param string $urlRequest
     * @param array $headers
     * @param string $body
     * @return array
     */
    protected function put($urlRequest, $headers, $body = '')
    {
        $request = new \GuzzleHttp\Psr7\Request('PUT', $urlRequest, $headers, $body);
        $result = $this->client->sendAsync($request)->wait();
        return [
            'code' => $result->getStatusCode(),
            'contents' => (string)$result->getBody()
        ];
    }

    /**
     * @param string $urlRequest
     * @param array $headers
     * @param string $body
     * @return array
     */
    protected function del($urlRequest, $headers, $body = '')
    {
        $request = new \GuzzleHttp\Psr7\Request('DELETE', $urlRequest, $headers, $body);
        $result = $this->client->sendAsync($request)->wait();
        return [
            'code' => $result->getStatusCode(),
            'contents' => (string)$result->getBody()
        ];
    }

    /**
     * @param string $endPoint
     * @param EntityInterface|null $entity
     * @param bool $allResponse
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException
     */
    public function getListRequest($endPoint, $entity = null, $allResponse = false)
    {
        $result = [];
        if ($allResponse) {
            $result = $this->getUrlGenericList($endPoint, $entity);
        } else {
            $result = $this->getUrlGenericListWithKey('results', $endPoint, $entity);
        }
        return $result;
    }

    /**
     * @param string $endPoint
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     * @throws UrlRuleRequestException|\Srdorado\SiigoClient\Exception\Rule\BadRequest
     */
    protected function getUrlGenericList($endPoint, EntityInterface $entity = null)
    {
        $result = [];
        $this->validator->validate($endPoint, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl($endPoint, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->get($urlRequest, $headers);
        if ($result['code'] === 200) {
            $result = json_decode($result['contents'], true);
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $result;
    }

    /**
     * @param string $key
     * @param string $endPoint
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     */
    protected function getUrlGenericListWithKey($key, $endPoint, $entity = null)
    {
        $response = [];
        $this->validator->validate($endPoint, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl($endPoint, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->get($urlRequest, $headers);
        if ($result['code'] === 200) {
            $result = json_decode($result['contents'], true);
            $response = $result[$key];
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $response;
    }

    /**
     * @param string $key
     * @param string $endPoint
     * @param EntityInterface|null $entity
     * @return string
     * @throws BadRequest
     */
    protected function getUrlGenericWithKey($key, $endPoint, $entity = null)
    {
        $response = '';
        $this->validator->validate($endPoint, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $url = $this->validator->getUrl($endPoint, $entity);
        $urlRequest = $this->getRequestUrl($url);
        $result = $this->get($urlRequest, $headers);
        if ($result['code'] === 200) {
            $result = json_decode($result['contents'], true);
            $response = $result[$key];
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $response;
    }

    /**
     * @param string $key
     * @param string $endPoint
     * @param EntityInterface|null $entity
     * @return string
     * @throws BadRequest
     */
    protected function getBodyGenericWithKey($key, $endPoint, $entity = null)
    {
        $response = [];
        $this->validator->validate($endPoint, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
        $body = $this->validator->getBody($endPoint, $entity);
        $urlRequest = $this->getRequestUrl($endPoint);
        $result = $this->post($urlRequest, $headers, json_encode($body));
        if ($result['code'] === 201) {
            $result = json_decode($result['contents'], true);
            $response = $result[$key];
        } else {
            $message =  'response - ' . $result['contents'];
            throw new \Srdorado\SiigoClient\Exception\Rule\BadRequest($message);
        }
        return $response;
    }

    /**
     * @param string $endPoint
     * @param EntityInterface|null $entity
     * @return array
     * @throws BadRequest
     */
    protected function getBodyGeneric($endPoint, $entity = null)
    {
        $response = [];
        $this->validator->validate($endPoint, $entity);
        $headers = $this->getHeaders(
            [
                'access_token' => $this->accessToken,
                'scope' => $this->scope
            ]
        );
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
     * @param array $params
     * @return array
     */
    abstract public function getHeaders($params = []);
}
