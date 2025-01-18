<?php

namespace Srdorado\SiigoClient\Model\RuleValidator;

use Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException;

class RuleSmallStringValidator implements RuleValidator
{
    public const MAX_LENGTH = 50;

    /**
     * @throws BodyRuleRequestException
     */
    public function validate($dataRule, $value)
    {
        if (!is_string($value) || strlen($value) > self::MAX_LENGTH) {
            $message = ' data is not valid, must be a string with a maximum of ' . self::MAX_LENGTH . ' characters';
            throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
        }

        if (strlen($dataRule) === 0) {
            return;
        }

        $domain = explode(',', $dataRule);

        if (!in_array($value, $domain)) {
            $message = ' data is not allowed, ' . $dataRule;
            throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
        }
    }
}
