<?php

namespace Srdorado\SiigoClient\Enum\Rule;

class CreditNote
{
    public const CREATE_JSON = [
        'document' => [
            'id' => Rule::INT
        ],
        '*number' => Rule::INT,
        'date' => Rule::DATE,
        'invoice' => Rule::STRING,
        '*cost_center' => Rule::INT,
        'reason' => Rule::SMALL_STRING . ':1,2,3,4,5',
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
        ]
    ];

    public const ID_PARAMS = [
        'credit_note_id' => Rule::STRING
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
