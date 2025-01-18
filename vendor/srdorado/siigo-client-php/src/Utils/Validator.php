<?php

namespace Srdorado\SiigoClient\Utils;

use Srdorado\SiigoClient\Exception\Rule\UrlRuleRequestException;
use Srdorado\SiigoClient\Factory\RuleFactory;
use Srdorado\SiigoClient\Model\EntityInterface;
use Srdorado\SiigoClient\Model\Validator\AbstractValidator;

class Validator
{
    private $ruleFactory;

    public function __construct()
    {
        $this->ruleFactory = new \Srdorado\SiigoClient\Factory\RuleFactory();
    }

    /**
     * @throws UrlRuleRequestException
     */
    public function validate($type, EntityInterface $entity, $rules, $endPoint = '')
    {
        switch ($type) {
            case AbstractValidator::URL_REQUEST:
                $this->validateUrlRequest($endPoint, $entity, $rules);
                break;
            case AbstractValidator::BODY_REQUEST:
                $this->validateBodyRequest($entity, $rules);
                break;
            case AbstractValidator::URL_BODY_REQUEST:
                $this->validateUrlWithBodyRequest($endPoint, $entity, $rules);
                break;
            default:
        }
    }

    /**
     * @throws UrlRuleRequestException
     */
    private function validateUrlRequest($endPoint, $entity, $rules)
    {
        $requireParams = substr_count($endPoint, '%s');
        $availableParams = $entity->countData();
        if ($requireParams !== $availableParams) {
            $message = 'params requiere: ' . $requireParams . ' - params available : ' . $availableParams;
            throw new UrlRuleRequestException($message);
        }
    }

    /**
     * @param EntityInterface $entity
     * @param array $rules
     * @return void
     * @throws \ReflectionException
     */
    private function validateBodyRequest($entity, $rules)
    {
        //TODO: add exception body
        $data = $entity->getData();

        $this->validateMatch($data, $rules);
    }

    /**
     * @throws UrlRuleRequestException|\ReflectionException
     */
    private function validateUrlWithBodyRequest($endPoint, $entity, $rules)
    {
        $entityClone = clone($entity);

        $data = $entityClone->getData();

        $dataClone = $data;

        unset($data[AbstractValidator::URL_REQUEST]);

        $dataClone = $dataClone[AbstractValidator::URL_REQUEST];

        $entityClone->setData($dataClone);

        $this->validateUrlRequest($endPoint, $entityClone, $rules);

        $entityClone->setData($data);

        $this->validateBodyRequest($entityClone, $rules);
    }

    /**
     * @param array $data
     * @param array $rules
     * @return void
     * @throws \ReflectionException
     */
    private function validateMatch($data, $rules)
    {
        foreach ($rules as $key => $rule) {
            try {
                if (strpos($key, '*') !== false) {
                    $tam = strlen($key);
                    $key = substr($key, 1, $tam);
                    if (!array_key_exists($key, $data)) {
                        continue;
                    }
                }

                $keyData = $key;

                if (is_numeric($key)) {
                    $keyData = intval($key);
                }

                if (is_array($rule)) {
                    $this->validateArray($data[$keyData], $rule);
                    continue;
                }

                $value = $data[$keyData];

                $customRules = explode('|', $rule);

                $this->validateCustomRules($customRules, $value);
            } catch (\Exception $e) {
                $message = $key . ' - ' . $e->getMessage();
                throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    private function validateArray($data, $rule)
    {
        if ($this->isArrayArray($rule)) {
            foreach ($data as $a) {
                $this->validateMatch($a, $rule[0]);
            }
        } else {
            $this->validateMatch($data, $rule);
        }
    }

    /**
     * @param array $array
     * @return bool
     */
    private function isArrayArray($array)
    {
        $result = true;
        foreach ($array as $key => $value) {
            if (!is_array($value)) {
                $result = false;
                break;
            }
        }
        return $result;
    }

    /**
     * @param $rules
     * @param $value
     * @return void
     * @throws \ReflectionException
     */
    private function validateCustomRules($rules, $value)
    {
        foreach ($rules as $rule) {
            $dataRule = explode(':', $rule);
            $ruleName = $dataRule[0];
            $ruleConstraint = count($dataRule) > 1 ? $dataRule[1] : '';
            if ($ruleName === \Srdorado\SiigoClient\Enum\Rule\Rule::OPTIONAL) {
                if ($value === '') {
                    break;
                } else {
                    continue;
                }
            }
            $modelRule = $this->ruleFactory->create($ruleName);
            $modelRule->validate($ruleConstraint, $value);
            unset($modelRule);
        }
    }
}
