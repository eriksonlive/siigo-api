<?php

namespace Srdorado\SiigoClient\Enum\Rule;

class Product
{
    public const CREATE_BASIC_JSON = [
        'code' => Rule::STRING ,
        'name' => Rule::STRING,
        'account_group' => Rule::INT
    ];

    public const CREATE_COMPLETE_JSON = [
        'code' => Rule::STRING,
        'name' => Rule::STRING,
        'account_group' => Rule::INT,
        'type' => Rule::SMALL_STRING . ':Product,Service,ConsumerGood',
        'stock_control' => Rule::BOOL,
        'active' => Rule::BOOL,
        'tax_classification' => Rule::SMALL_STRING . ':Taxed,Exempt,Excluded',
        'tax_included' => Rule::BOOL,
        'tax_consumption_value' => Rule::INT,
        'taxes' => [
            // List
            [
                'id' => Rule::INT
            ]
        ],
        'prices' => [
            //list
            [
                'currency_code' => Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':5',
                'price_list' => [
                    [
                        'position' => Rule::INT,
                        'value' => Rule::FLOAT
                    ]
                ]
            ]
        ],
        'unit' => [
            'code' => Rule::SMALL_STRING,
            'name' => Rule::SMALL_STRING,
        ],
        'unit_label' => Rule::SMALL_STRING,
        'reference' => Rule::STRING,
        'description' => Rule::STRING,
        'additional_fields' => [
            'barcode' => Rule::SMALL_STRING,
            'brand' => Rule::SMALL_STRING,
            'tariff' => Rule::SMALL_STRING,
            'model' => Rule::SMALL_STRING
        ],
        'available_quantity' => Rule::INT,
        'warehouses' => Rule::_ARRAY
    ];

    public const GET_BY_ID_PARAMS = [
        'product_id' => Rule::STRING,
    ];

    public const GET_BY_CREATED_START_PARAMS = [
        'created_start' => Rule::DATE,
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];

    public const GET_BY_UPDATED_START_PARAMS = [
        'updated_start' => Rule::DATE,
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];

    public const GET_BY_CREATED_END_PARAMS = [
        'created_end' => Rule::DATE,
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];

    public const GET_BY_UPDATED_END_PARAMS = [
        'updated_end' => Rule::DATE,
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];

    public const GET_BY_CODE_PARAMS = [
        'code' => Rule::STRING,
    ];

    public const DELETE_PARAMS = [
        'product_id' => Rule::STRING,
    ];

    public const GET_ALL_PARAMS = [
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];
}
