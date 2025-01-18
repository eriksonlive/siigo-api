<?php

namespace Srdorado\SiigoClient\Exception\Rule;

use Srdorado\SiigoClient\Exception\SiigoException;

class BadRequest extends SiigoException
{
    public function __construct($message = '', $code = 0, $previous = null)
    {
        $defaultMessage =  ' Bad request or invalid token. ';
        $defaultMessage = $this->headMessage . $defaultMessage;
        $message = $defaultMessage . $message;
        $code = self::BAD_REQUEST;
        parent::__construct($message, $code, $previous);
    }
}
