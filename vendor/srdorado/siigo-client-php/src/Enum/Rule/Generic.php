<?php

namespace Srdorado\SiigoClient\Enum\Rule;

class Generic
{
    public const GET_DOCUMENT_TYPES_PARAMS = [
        'type' => Rule::SMALL_STRING . ':FV,NC,RC,FC,CC'
    ];

    public const GET_USERS_PARAMS = [
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];

    public const GET_PAYMENT_TYPES_PARAMS = [
        'document_type' =>  Rule::SMALL_STRING . ':FV,NC,RC,FC,CC'
    ];
}
