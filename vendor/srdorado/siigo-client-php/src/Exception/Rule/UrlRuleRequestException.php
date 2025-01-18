<?php

namespace Srdorado\SiigoClient\Exception\Rule;

use Srdorado\SiigoClient\Exception\SiigoException;

class UrlRuleRequestException extends SiigoException
{
    public function __construct($message = '', $code = 0, $previous = null)
    {
        $defaultMessage =  ' number of parameters does not match the parameters required in the request. ';
        $defaultMessage = $this->headMessage . $defaultMessage;
        $message = $defaultMessage . $message;
        $code = self::INCORRECT_NUMBER_PARAMETERS;
        parent::__construct($message, $code, $previous);
    }
}
