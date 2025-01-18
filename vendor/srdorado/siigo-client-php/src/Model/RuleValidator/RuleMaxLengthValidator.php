<?php

namespace Srdorado\SiigoClient\Model\RuleValidator;

use Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException;

class RuleMaxLengthValidator
{
    /**
     * @throws BodyRuleRequestException
     */
    public function validate($dataRule, $value)
    {
        $maxLength = intval($dataRule);

        if (!is_string($value) || strlen($value) > $maxLength) {
            $message = ' data is not valid, must be a string with a maximum of ' . $maxLength . ' characters';
            throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
        }
    }
}
