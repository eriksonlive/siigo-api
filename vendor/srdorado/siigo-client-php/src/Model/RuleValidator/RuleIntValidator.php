<?php

namespace Srdorado\SiigoClient\Model\RuleValidator;

use Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException;
use Srdorado\SiigoClient\Utils\Utils;

class RuleIntValidator implements RuleValidator
{
    /**
     * @throws BodyRuleRequestException
     */
    public function validate($dataRule, $value)
    {
        if (!is_numeric($value)) {
            $message = ' data not is numeric';
            throw new \Srdorado\SiigoClient\Exception\Rule\BodyRuleRequestException($message);
        }
    }
}
