<?php

namespace Srdorado\SiigoClient\Enum\Rule;

class Journal
{
    public const CURRENCIES = 'COP,EUR,USD,ANG,ARS,AUD,BOB,BRL,CAD,CHF,CLP,CRC,GBP,GTQ,HNL,JPY,MXN,NZD,PAB,PEN,SGD,UYU';

    public const CREATE_JSON = [
        'document' => [
            'id' => Rule::INT
        ],
        'date' => Rule::DATE,
        'items' => [
            // List
            [
                'account' => [
                    'code' => Rule::SMALL_STRING,
                    'movement' => Rule::SMALL_STRING . ':Credit,Debit'
                ],
                'customer' => [
                    'identification' =>  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':13',
                    'branch_office' => Rule::INT
                ],
                'description' =>  Rule::SMALL_STRING,
                'cost_center' => Rule::INT,
                'value' =>  Rule::FLOAT
            ]
        ],
        '*observations' => Rule::OPTIONAL . '|' . Rule::STRING
    ];

    public const ID_PARAMS = [
        'journal_id' => Rule::STRING
    ];

    public const GEL_ALL_PARAMS = [
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];

    public const GET_DATE_PARAMS = [
        'date' => Rule::DATE,
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];

    public const GET_GENERIC_LIST_PARAMS = [
        'generic' => Rule::STRING,
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];
}
