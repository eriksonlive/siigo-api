<?php

namespace Srdorado\SiigoClient\Model\Validator;

use Srdorado\SiigoClient\Enum\Rule\Product as Rule;
use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Enum\EndPoint\Product as EndPoint;

class ProductValidator extends AbstractValidator
{
    /**
     * @param string $endPoint
     * @param EntityInterface|null $entity
     * @return void
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
            case EndPoint::GET_BY_CODE:
                $this->validator->validate(
                    AbstractValidator::URL_REQUEST,
                    $entity,
                    Rule::GET_BY_CODE_PARAMS,
                    EndPoint::GET_BY_CODE
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
            case EndPoint::WAREHOUSES:
                // no validate warehouses
                break;
            case EndPoint::ACCOUNT_GROUPS:
                //no vaidate account groups
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
