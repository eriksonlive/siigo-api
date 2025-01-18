<?php

namespace Srdorado\SiigoClient\Model\Validator;

use Srdorado\SiigoClient\Enum\Rule\Token;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Enum\EndPoint\Token as EndPointToken;

class TokenValidator extends AbstractValidator
{
    /**
     * @throws UrlRuleRequestException
     */
    public function validate($endPoint, EntityInterface $entity = null)
    {
        if (\Srdorado\SiigoClient\Enum\EndPoint\Token::AUTH !== $endPoint) {
            $message = ' endpoint does not exist.';
            throw new UrlRuleRequestException($message);
        }

        $this->validator->validate(AbstractValidator::BODY_REQUEST, $entity, Token::AUTH, EndPointToken::AUTH);
    }

    /**
     * @param string $endPoint
     * @param EntityInterface $entity
     * @return array
     */
    public function getBody($endPoint, EntityInterface $entity)
    {
        $rules = [];

        if (\Srdorado\SiigoClient\Enum\EndPoint\Token::AUTH === $endPoint) {
            $rules = \Srdorado\SiigoClient\Enum\Rule\Token::AUTH;
        }

        return $this->bodyFactory->getBody($entity, $rules);
    }
}
