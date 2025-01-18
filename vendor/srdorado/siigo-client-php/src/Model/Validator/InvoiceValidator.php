<?php

namespace Srdorado\SiigoClient\Model\Validator;

use Srdorado\SiigoClient\Enum\Rule\Invoice as Rule;
use Srdorado\SiigoClient\Enum\Rule\Customer as CustomerRule;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Enum\EndPoint\Invoice as EndPoint;

class InvoiceValidator extends AbstractValidator
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
            EndPoint::GET_BY_NAME,
            EndPoint::GET_BY_CUSTOMER_IDENTIFICATION,
            EndPoint::GET_BY_DOCUMENT_ID
        ];

        $otherEndpoints = [
            EndPoint::ANNUL,
            EndPoint::PDF,
            EndPoint::STAMP_ERRORS
        ];

        if ($endPoint ===  EndPoint::CREATE || $endPoint ===  EndPoint::UPDATE . 'U') {
            $rules = Rule::CREATE_JSON;
            $data = $entity->getData();
            if (count($data['customer']) != 2) {
                $rules['customer'] = CustomerRule::CREATE_COMPLETE_JSON;
            }
            $this->validator->validate(
                AbstractValidator::BODY_REQUEST,
                $entity,
                $rules,
                ($endPoint === EndPoint::CREATE ? EndPoint::CREATE : EndPoint::UPDATE)
            );
        } elseif ($endPoint === EndPoint::MAIL) {
            $this->validator->validate(
                AbstractValidator::BODY_REQUEST,
                $entity,
                Rule::GET_EMAIL_PARAMS,
                $endPoint
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
        } elseif ($endPoint ===  EndPoint::GET . 'G') {
            $this->validator->validate(
                AbstractValidator::URL_REQUEST,
                $entity,
                Rule::ID_PARAMS,
                EndPoint::GET
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
        $rules = [];
        if ($endPoint === EndPoint::CREATE || $endPoint === EndPoint::UPDATE) {
            $rules = Rule::CREATE_JSON;
            $data = $entity->getData();
            if (count($data['customer']) != 2) {
                $rules['customer'] = CustomerRule::CREATE_COMPLETE_JSON;
            }
        }
        return $this->bodyFactory->getBody($entity, $rules);
    }
}
