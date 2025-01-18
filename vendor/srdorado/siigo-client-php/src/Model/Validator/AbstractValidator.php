<?php

namespace Srdorado\SiigoClient\Model\Validator;

use Srdorado\SiigoClient\Model\EntityInterface;

abstract class AbstractValidator
{
    public const URL_REQUEST = 'url_request';
    public const BODY_REQUEST = 'body_request';
    public const URL_BODY_REQUEST = 'url_body_request';

    protected $validator;

    protected $bodyFactory;

    protected $urlFactory;

    public function __construct()
    {
        $this->validator = new \Srdorado\SiigoClient\Utils\Validator();
        $this->bodyFactory = new \Srdorado\SiigoClient\Utils\BodyFactory();
        $this->urlFactory = new \Srdorado\SiigoClient\Utils\UrlFactory();
    }

    abstract public function validate($endPoint, EntityInterface $entity = null);

    abstract public function getBody($endPoint, EntityInterface $entity);

    /**
     * @param string $endPoint
     * @param EntityInterface|null $entity
     * @return string
     */
    public function getUrl($endPoint, EntityInterface $entity = null)
    {
        return $this->urlFactory->getUrl($endPoint, $entity);
    }
}
