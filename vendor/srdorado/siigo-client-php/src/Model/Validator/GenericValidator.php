<?php

namespace Srdorado\SiigoClient\Model\Validator;

use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Enum\Rule\Generic as Rule;
use Srdorado\SiigoClient\Enum\EndPoint\Generic as EndPoint;

class GenericValidator extends AbstractValidator
{
    public function validate($endPoint, EntityInterface $entity = null)
    {
        switch ($endPoint) {
            case EndPoint::GET_FIXED_ASSETS:
                // no validate fixes assets
                break;
            case EndPoint::GET_COST_CENTERS:
                // no validate cost centers
                break;
            case EndPoint::GET_PAYMENT_TYPES:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_PAYMENT_TYPES_PARAMS,
                    EndPoint::GET_PAYMENT_TYPES
                );
                break;
            case EndPoint::GET_USERS:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_USERS_PARAMS,
                    EndPoint::GET_USERS
                );
                break;
            case EndPoint::TAXES:
               // no validate taxes
                break;
            case EndPoint::GET_PRICE_LISTS:
                // no validate
                break;
            case EndPoint::GET_DOCUMENT_TYPES:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_DOCUMENT_TYPES_PARAMS,
                    EndPoint::GET_DOCUMENT_TYPES
                );
                break;
            default:
                $message = ' endpoint does not exist.';
                throw new UrlRuleRequestException($message);
        }
    }

    public function getBody($endPoint, EntityInterface $entity)
    {
        // TODO: Implement getBody() method.
        return $this->bodyFactory->getBody($entity, []);
    }
}
