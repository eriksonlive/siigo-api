<?php

namespace Srdorado\SiigoClient\Exception;

class SiigoException extends \Exception
{
    public const BAD_REQUEST = 20000;
    public const INCORRECT_NUMBER_PARAMETERS = 20001;
    public const MALFORMED_RULE_OR_INVALID_PARAMS = 20002;

    protected $headMessage = 'Siigo: ';
}
