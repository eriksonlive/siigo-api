<?php

namespace Srdorado\SiigoClient\Enum\Rule;

class Token
{
    public const AUTH = [
        'username' => Rule::E_MAIL,
        'access_key' => Rule::LONG_STRING
    ];
}
