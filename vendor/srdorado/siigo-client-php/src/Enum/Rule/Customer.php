<?php

namespace Srdorado\SiigoClient\Enum\Rule;

class Customer
{
    //USE UPDATE AND CREATE
    public const CREATE_BASIC_JSON = [
        'person_type' => Rule::SMALL_STRING . ':Person,Company',
        'id_type' => Rule::SMALL_STRING . ':13,31,22,42,50,R-00-PN,91,41,47,11,43,21,12', //Values in Colombia
        'identification' => Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':13',
        'name' => [
            '0' => Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30',
            '1' => Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30'
        ],
        'address' => [
            'address' => Rule::STRING,
            'city' => [
                'country_code' => Rule::SMALL_STRING  . '|' . Rule::MAX_LENGTH . ':3',
                'state_code' => Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':30',
                'city_code' => Rule::NUM_STRING  . '|' . Rule::MIN_LENGTH . ':5' .  '|' . Rule::MAX_LENGTH . ':8'
            ]
        ],
        'phones' => [
            //list
            [
                'number' =>  Rule::NUM_STRING  . '|' . Rule::LENGTH . ':10',
            ]
        ],
        'contacts' => [
            //list
            [
                'first_name' => Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30',
                'last_name' => Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30',
                'email' => Rule::E_MAIL,
                'phone' => [
                    'indicative' =>  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':3',
                    'number' =>  Rule::NUM_STRING  . '|' . Rule::LENGTH . ':10',
                    'extension' =>  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':3'
                ]
            ]
        ]
    ];

    public const CREATE_COMPLETE_JSON = [
        'type' => Rule::SMALL_STRING . ':Customer,Supplier,Other',
        'person_type' => Rule::SMALL_STRING . ':Person,Company',
        'id_type' => Rule::SMALL_STRING . ':13,31,22,42,50,R-00-PN,91,41,47,11,43,21,12', //Values in Colombia
        'identification' => Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':13',
        '*check_digit' => Rule::OPTIONAL . '|' . Rule::SMALL_STRING . ':1,2,3,4,5,6,7,8,9',
        'name' => [
            '0' => Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30'
            //'1' => Rule::OPTIONAL . '|' . Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30'
        ],
        'commercial_name' => Rule::OPTIONAL . '|' . Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30',
        'branch_office' => Rule::INT,
        'active' => Rule::BOOL,
        'vat_responsible' => Rule::BOOL,
        'fiscal_responsibilities' => [
            [
              'code' => Rule::SMALL_STRING . ':R-99-PN,O-13,O-15,O-23,O-47'
            ]
        ],
        'address' => [
            'address' => Rule::STRING,
            'city' => [
                'country_code' => Rule::SMALL_STRING  . '|' . Rule::MAX_LENGTH . ':3',
                'state_code' => Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':30',
                'city_code' => Rule::NUM_STRING  . '|' . Rule::MIN_LENGTH . ':5' .  '|' . Rule::MAX_LENGTH . ':8'
            ],
            'postal_code' => Rule::NUM_STRING  . '|' . Rule::MIN_LENGTH . ':5' .  '|' . Rule::MAX_LENGTH . ':8'
        ],
        'phones' => [
            //list
            [
                'indicative' =>  Rule::OPTIONAL  . '|' .  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':3',
                'number' =>  Rule::NUM_STRING  . '|' . Rule::LENGTH . ':10',
                'extension' =>  Rule::OPTIONAL  . '|' .  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':3'
            ]
        ],
        'contacts' => [
            //list
            [
                'first_name' => Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30',
                'last_name' => Rule::SMALL_STRING . '|' . Rule::MAX_LENGTH . ':30',
                'email' => Rule::E_MAIL,
                'phone' => [
                    'indicative' =>  Rule::OPTIONAL  . '|' .  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':3',
                    'number' =>  Rule::NUM_STRING  . '|' . Rule::LENGTH . ':10',
                    'extension' =>  Rule::OPTIONAL  . '|' .  Rule::NUM_STRING  . '|' . Rule::MAX_LENGTH . ':3'
                ],
            ]
        ],
        'comments' => Rule::SMALL_STRING  . '|' . Rule::MAX_LENGTH . ':150',
        '*related_users' => [
            'seller_id' => Rule::INT,
            'collector_id' => Rule::INT
        ],
    ];

    public const GET_BY_ID_PARAMS = [
        'customer_id' => Rule::STRING
    ];

    public const GET_BY_BRANCH_OFFICE_PARAMS = [
        'branch_office' => Rule::INT,
         'page' => Rule::INT,
         'page_size' => Rule::INT
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

    public const DELETE_PARAMS = [
        'customer_id' => Rule::STRING
    ];

    public const GET_ALL_PARAMS = [
        'page' => Rule::INT,
        'page_size' => Rule::INT
    ];
}
