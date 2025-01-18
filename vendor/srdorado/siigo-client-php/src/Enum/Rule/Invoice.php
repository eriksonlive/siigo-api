<?php

namespace Srdorado\SiigoClient\Enum\Rule;

class Invoice
{
    public const CURRENCIES = 'COP,EUR,USD,ANG,ARS,AUD,BOB,BRL,CAD,CHF,CLP,CRC,GBP,GTQ,HNL,JPY,MXN,NZD,PAB,PEN,SGD,UYU';

    public const CREATE_JSON = [
        'document' => [
            'id' => Rule::INT
        ],
        '*number' => Rule::INT,
        'date' => Rule::DATE,
        'customer' => [
            'identification' =>  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':13',
            'branch_office' => Rule::INT
        ],
        '*cost_center' => Rule::INT,
        '*currency' => [
            'code' => Rule::SMALL_STRING . ':' . self::CURRENCIES,
            'exchange_rate' => Rule::FLOAT
        ],
        'seller' => Rule::INT,
        '*stamp' => [
            'send' => Rule::BOOL
        ],
        '*mail' => [
            'send' => Rule::BOOL
        ],
        '*retentions' => [
            [
              'id' => Rule::INT
            ]
        ],
        '*observations' => Rule::OPTIONAL . '|' . Rule::STRING,
        'items' => [
            // List
            [
                'code' => Rule::STRING,
                'description' => Rule::STRING,
                'quantity' => Rule::INT,
                'taxes' => [
                    // List
                    [
                        'id' => Rule::INT
                    ]
                ],
                '*taxed_price' => Rule::FLOAT,
                'price' => Rule::FLOAT,
                'discount' =>  Rule::FLOAT,
            ]
        ],
        'payments' => [
            // List
            [
                'id' => Rule::INT,
                'value' => Rule::FLOAT,
                'due_date' => Rule::DATE
            ]
        ],
        '*additional_fields' => Rule::_ARRAY
    ];

    public const ID_PARAMS = [
        'invoice_id' => Rule::STRING
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

    public const GET_EMAIL_PARAMS = [
        'mail_to' => Rule::E_MAIL,
        'copy_to' => Rule::STRING
    ];
}
