<?php

namespace Srdorado\SiigoClient\Model\RuleValidator;

use Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException;

class RuleArrayValidator implements RuleValidator
{
    /**
     * @throws BodyRuleRequestException
     */
    public function validate($dataRule, $value)
    {
        if (!is_array($value)) {
            $message = ' data is not valid, must be a array';
            throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
        }

        if (strlen($dataRule) === 0) {
            return;
        }

        foreach ($value as $val) {
            if (is_array($val)) {
                $message = ' data is not valid, must be int or string';
                throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
            }
        }
    }
}
