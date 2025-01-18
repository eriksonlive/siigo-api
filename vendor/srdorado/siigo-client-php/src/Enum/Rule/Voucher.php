<?php

namespace Srdorado\SiigoClient\Enum\Rule;

class Voucher
{
    public const CURRENCIES = 'COP,EUR,USD,ANG,ARS,AUD,BOB,BRL,CAD,CHF,CLP,CRC,GBP,GTQ,HNL,JPY,MXN,NZD,PAB,PEN,SGD,UYU';

    public const CREATE_JSON = [
        'document' => [
            'id' => Rule::INT
        ],
        'date' => Rule::DATE,
        'type' => Rule::SMALL_STRING . ':DebtPayment,AdvancePayment,Detailed',
        'customer' => [
            'identification' =>  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':13',
            'branch_office' => Rule::INT
        ],
        '*currency' => [
            'code' => Rule::SMALL_STRING . ':' . self::CURRENCIES,
            'exchange_rate' => Rule::FLOAT
        ],
        '*cost_center' => Rule::INT,
        'items' => [
            // List
            [
                'due' => [
                    'prefix' => Rule::SMALL_STRING,
                    'consecutive' => Rule::INT,
                    'quote' => Rule::INT,
                    'date' => Rule::DATE
                ],
                'value' =>  Rule::FLOAT
            ]
        ],
        'payments' => [
            // List
            [
                'id' => Rule::INT,
                'value' => Rule::FLOAT,
            ]
        ],
        '*observations' => Rule::OPTIONAL . '|' . Rule::STRING
    ];

    public const CREATE_JSON_ADVANCED = [
        'document' => [
            'id' => Rule::INT
        ],
        'date' => Rule::DATE,
        'type' => Rule::SMALL_STRING . ':DebtPayment,AdvancePayment,Detailed',
        'customer' => [
            'identification' =>  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':13',
            'branch_office' => Rule::INT
        ],
        '*currency' => [
            'code' => Rule::SMALL_STRING . ':' . self::CURRENCIES,
            'exchange_rate' => Rule::FLOAT
        ],
        '*cost_center' => Rule::INT,
        'items' => [
            // List
            [
                'account' => [
                    'code' => Rule::SMALL_STRING,
                    'movement' => Rule::SMALL_STRING . ':Credit,Debit'
                ],
                'description' =>  Rule::SMALL_STRING,
                'value' =>  Rule::FLOAT
            ]
        ],
        '*payments' => [
            // List
            [
                'id' => Rule::INT,
                'value' => Rule::FLOAT,
            ]
        ],
        '*observations' => Rule::OPTIONAL . '|' . Rule::STRING
    ];

    public const ID_PARAMS = [
        'voucher_id' => Rule::STRING
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
