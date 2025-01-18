<?php

namespace Srdorado\SiigoClient\Model\Validator;

use Srdorado\SiigoClient\Enum\Rule\Customer as Rule;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Enum\EndPoint\Customer as EndPoint;

class CustomerValidator extends AbstractValidator
{
    /**
     * @throws UrlRuleRequestException
     */
    public function validate($endPoint, EntityInterface $entity = null)
    {
        switch ($endPoint) {
            case EndPoint::CREATE:
                // if complete or nomral
                $rule = Rule::CREATE_BASIC_JSON;
                if (count(Rule::CREATE_BASIC_JSON) != $entity->countData()) {
                    $rule = Rule::CREATE_COMPLETE_JSON;
                }
                $this->validator->validate(
                    AbstractValidator::BODY_REQUEST,
                    $entity,
                    $rule,
                    EndPoint::CREATE
                );
                //request body
                break;
            case EndPoint::UPDATE . 'U':
                //AbstractValidator::URL_REQUEST]
                $rule = Rule::CREATE_BASIC_JSON;
                if (count(Rule::CREATE_BASIC_JSON) != $entity->countData() - 1) {
                    $rule = Rule::CREATE_COMPLETE_JSON;
                }
                $this->validator->validate(
                    AbstractValidator::URL_BODY_REQUEST,
                    $entity,
                    $rule,
                    EndPoint::UPDATE
                );
                break;
            case EndPoint::GET_ALL:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_ALL_PARAMS,
                    EndPoint::GET_ALL
                );
                //NO validation
                break;
            case EndPoint::GET_BY_BRANCH_OFFICE:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_BY_BRANCH_OFFICE_PARAMS,
                    EndPoint::GET_BY_BRANCH_OFFICE
                );
                break;
            case EndPoint::GET_BY_CREATED_START:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_BY_CREATED_START_PARAMS,
                    EndPoint::GET_BY_CREATED_START
                );
                break;
            case EndPoint::GET_BY_UPDATED_START:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_BY_UPDATED_START_PARAMS,
                    EndPoint::GET_BY_UPDATED_START
                );
                break;
            case EndPoint::GET_BY_CREATED_END:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_BY_CREATED_END_PARAMS,
                    EndPoint::GET_BY_CREATED_END
                );
                break;
            case EndPoint::GET_BY_UPDATED_END:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_BY_UPDATED_END_PARAMS,
                    EndPoint::GET_BY_UPDATED_END
                );
                break;
            case EndPoint::GET_BY_ID:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_BY_ID_PARAMS,
                    EndPoint::GET_BY_ID
                );
                break;
            case EndPoint::DELETE . 'D':
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::DELETE_PARAMS,
                    EndPoint::DELETE
                );
                break;
            default:
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
            $rules = Rule::CREATE_BASIC_JSON;
            if (count(Rule::CREATE_COMPLETE_JSON) === $entity->countData()) {
                $rules = Rule::CREATE_COMPLETE_JSON;
            }
        }
        return $this->bodyFactory->getBody($entity, $rules);
    }
}
