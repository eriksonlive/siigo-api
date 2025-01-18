<?php

namespace Srdorado\SiigoClient\Model\RuleValidator;

use Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException;

class RuleMinLengthValidator
{
    /**
     * @throws BodyRuleRequestException
     */
    public function validate($dataRule, $value)
    {
        $minLength = intval($dataRule);

        if (!is_string($value) || strlen($value) < $minLength) {
            $message = ' data is not valid, must be a string with a minimun of ' . $minLength . ' characters';
            throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
        }
    }
}
