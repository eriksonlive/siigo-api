<?php

namespace Srdorado\SiigoClient\Tests;

use PHPUnit\Framework\TestCase;

class ClientInvoiceTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $clientInvoice = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::INVOICE);

        $dataEntity = $this->getJsonWitCustomer();

        $entity->setData($dataEntity);

        $invoiceId = $clientInvoice->create($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function update()
    {
        $clientInvoice = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::INVOICE);

        $dataEntity = $this->getJsonWitCustomer();

        $dataEntity = array_merge(
            $dataEntity,
            [
                'url_request' => [
                    'invoice_id' => '4c75dda1-aeeb-4a5a-8e09-110d849f1547'
                ]
            ]
        );

        $entity->setData($dataEntity);

        $invoiceId = $clientInvoice->update($entity);

        echo $invoiceId;

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getAll()
    {
        $clientInvoice = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::INVOICE);

        $entity->setData(
            [
                'page' => 5,
                'page_size' => 25,
            ]
        );

        $invoices = $clientInvoice->getAll($entity);

        $this->assertTrue(true);
    }


    /**
     * @return mixed
     * @throws \ReflectionException
     */
    private function getCustomClient()
    {
        // generate token
        $token = $this->getToken();

        // Create client
        $clientFactory = new \Srdorado\SiigoClient\Factory\ClientFactory();
        $clientCustomerFactory = $clientFactory->create(\Srdorado\SiigoClient\Enum\ClientType::INVOICE);
        $clientInvoice = $clientCustomerFactory->create();
        $clientInvoice->setBaseUrl('https://api.siigo.com/');
        $clientInvoice->setAccessKey($token);

        return $clientInvoice;
    }

    /**
     * Get token
     *
     * @return string
     * @throws \ReflectionException
     */
    private function getToken(): string
    {
        // Create client token
        $clientFactory = new \Srdorado\SiigoClient\Factory\ClientFactory();
        $clientTokenFactory = $clientFactory->create(\Srdorado\SiigoClient\Enum\ClientType::TOKEN);
        $clientToken = $clientTokenFactory->create();
        $clientToken->setBaseUrl('https://api.siigo.com/');

        // Create entity token
        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::TOKEN);
        $entity->setData(
            [
                'username' => '',
                'access_key' => ''
            ]
        );

        // Request token
        return $clientToken->getToken($entity);
    }


    /**
     * @return array
     */
    private function getJsonWithoutCustomer()
    {
        return [
            'document' => [
                'id' => 24446
            ],
            'date' => '2022-10-24',
            'customer' => [
                'identification' => '209048401',
                'branch_office' => '0'
            ],
            'cost_center' => 235,
            'currency' => [
                'code' => 'USD',
                'exchange_rate' => 3825.03
            ],
            'seller' => 629,
            'stamp' => [
                'send' => false
            ],
            'mail' => [
                'send' => false
            ],
            'observations' => 'Observaciones',
            'items' => [
                [
                    'code' => 'Sku-1',
                    'description' => 'Sku-1',
                    'quantity' => 1,
                    'taxes' => [
                        [
                            'id' => 13156
                        ],
                        [
                            'id' => 21479
                        ]
                    ],
                    //'taxed_price' => 1000,
                    'price' => 847.45,
                    'discount' => 0
                ]
            ],
            'payments' => [
                [
                    'id' => 5638,
                    'value' => 1000,
                    'due_date' => '2022-10-24'
                ]
            ]//,
            //'additional_fields' => []
        ];
    }

    /**
     * @return array
     */
    private function getJsonWitCustomer()
    {
        return [
            'document' => [
                'id' => 24446
            ],
            'date' => '2022-11-23',
            'customer' => [
                'type' => 'Customer',
                'person_type' => 'Person',
                'id_type' => '13',
                'identification' => '24545225471',
                //'check_digit' => '',
                'name' => [
                    'Manuel',
                    'Camacho'
                ],
                'commercial_name' => '',
                'branch_office' => 0,
                'active' => true,
                'vat_responsible' => false,
                'fiscal_responsibilities' => [
                    [
                        'code' => 'R-99-PN'
                    ]
                ],
                'address' => [
                    'address' => 'Cra. 18 #79A - 42',
                    'city' => [
                        'country_code' => 'Co',
                        'state_code' => '11',
                        'city_code' => '11001'
                    ],
                    'postal_code' => '110911'
                ],
                'phones' => [
                    [
                        'indicative' => '',
                        'number' => '3006003344',
                        'extension' => ''
                    ]
                ],
                'contacts' => [
                    [
                        'first_name' => 'Manuel',
                        'last_name' => 'Camacho',
                        'email' => 'manuel.camacho@contacto.com',
                        'phone' => [
                            'indicative' => '',
                            'number' => '3005003345',
                            'extension' => ''
                        ]
                    ]
                ],
                'comments' => ''
            ],
            'cost_center' => 235,
            'currency' => [
                'code' => 'USD',
                'exchange_rate' => 3825.03
            ],
            'seller' => 629,
            'stamp' => [
                'send' => false
            ],
            'mail' => [
                'send' => false
            ],
            'observations' => 'Observaciones eeeee uuu',
            'items' => [
                [
                    'code' => 'Sku-1',
                    'description' => 'Sku-1',
                    'quantity' => 1,
                    'taxes' => [
                        [
                            'id' => 13156
                        ],
                        [
                            'id' => 21479
                        ]
                    ],
                    //'taxed_price' => 1000,
                    'price' => 847.45,
                    'discount' => 0
                ]
            ],
            'payments' => [
                [
                    'id' => 5638,
                    'value' => 1000,
                    'due_date' => '2022-11-23'
                ]
            ]//,
            //'additional_fields' => []
        ];
    }
}
