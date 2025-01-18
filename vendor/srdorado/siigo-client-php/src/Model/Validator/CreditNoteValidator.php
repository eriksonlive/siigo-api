<?php

namespace Srdorado\SiigoClient\Model\Validator;

use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Enum\Rule\CreditNote as Rule;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Enum\EndPoint\CreditNote as EndPoint;

class CreditNoteValidator extends AbstractValidator
{
    /**
     * @throws UrlRuleRequestException
     */
    public function validate($endPoint, EntityInterface $entity = null)
    {
        $dateEndpoints = [
            EndPoint::GET_BY_CREATED_START,
            EndPoint::GET_BY_UPDATED_START,
            EndPoint::GET_BY_DATE_START,
            EndPoint::GET_BY_DATE_END,
            EndPoint::GET_BY_CREATED_END,
            EndPoint::GET_BY_UPDATED_END
        ];

        $genericEndpoints = [
            EndPoint::GET_BY_CUSTOMER_BRANCH_OFFICE,
            EndPoint::GET_BY_CUSTOMER_IDENTIFICATION,
            EndPoint::GET_BY_DOCUMENT_ID
        ];

        $otherEndpoints = [
            EndPoint::PDF
        ];

        if ($endPoint ===  EndPoint::CREATE) {
            $rules = Rule::CREATE_JSON;
            $this->validator->validate(
                AbstractValidator::BODY_REQUEST,
                $entity,
                $rules,
                EndPoint::CREATE
            );
        } elseif ($endPoint ===  EndPoint::GET_BY_ID) {
            $this->validator->validate(
                AbstractValidator::URL_REQUEST,
                $entity,
                Rule::ID_PARAMS,
                EndPoint::GET_BY_ID
            );
        } elseif ($endPoint === EndPoint::GET_ALL) {
            $this->validator->validate(
                AbstractValidator::URL_REQUEST,
                $entity,
                Rule::GEL_ALL_PARAMS,
                EndPoint::GET_ALL
            );
        } elseif (in_array($endPoint, $dateEndpoints)) {
            $this->validator->validate(
                AbstractValidator::URL_REQUEST,
                $entity,
                Rule::GET_DATE_PARAMS,
                $endPoint
            );
        } elseif (in_array($endPoint, $genericEndpoints)) {
            $this->validator->validate(
                AbstractValidator::URL_REQUEST,
                $entity,
                Rule::GET_GENERIC_LIST_PARAMS,
                $endPoint
            );
        } elseif (in_array($endPoint, $otherEndpoints)) {
            $this->validator->validate(
                AbstractValidator::URL_REQUEST,
                $entity,
                Rule::ID_PARAMS,
                $endPoint
            );
        } else {
            $message = ' endpoint does not exist.';
            throw new UrlRuleRequestException($message);
        }
    }

    /**
     * @param string $endPoint
     * @param EntityInterface $entity
     * @return array
     */
    public function getBody($endPoint, EntityInterface $entity)
    {
        $rules = Rule::CREATE_JSON;
        return $this->bodyFactory->getBody($entity, $rules);
    }
}
