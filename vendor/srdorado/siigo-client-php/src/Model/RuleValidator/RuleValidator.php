<?php

namespace Srdorado\SiigoClient\Model\RuleValidator;

interface RuleValidator
{
    public function validate($dataRule, $value);
}
