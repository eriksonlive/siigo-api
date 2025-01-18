<?php

namespace Srdorado\SiigoClient\Model\RuleValidator;

use Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException;

class RuleBoolValidator implements RuleValidator
{
    /**
     * @throws BodyRuleRequestException
     */
    public function validate($dataRule, $value)
    {
        if (!is_bool($value)) {
            $message = ' data is not of type boolean';
            throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
        }
    }
}
