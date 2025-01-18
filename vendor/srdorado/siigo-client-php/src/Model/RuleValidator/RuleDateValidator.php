<?php

namespace Srdorado\SiigoClient\Model\RuleValidator;

use Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException;
use Srdorado\SiigoClient\Utils\Utils;

class RuleDateValidator implements RuleValidator
{
    /**
     * @throws BodyRuleRequestException
     */
    public function validate($dataRule, $value)
    {
        if (!Utils::isDateValid('Y-m-d', $value)) {
            $message = ' date is not valid, format: Y-m-d';
            throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
        }
    }
}
