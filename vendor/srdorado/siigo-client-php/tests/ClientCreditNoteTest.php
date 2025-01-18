<?php

namespace Srdorado\SiigoClient\Tests;

use PHPUnit\Framework\TestCase;

class ClientCreditNoteTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $clientCN = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::CREDIT_NOTE);

        $dataEntity = $this->getJson();

        $entity->setData($dataEntity);

        $response = $clientCN->create($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getAll()
    {
        $clientCN = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::CREDIT_NOTE);

        $entity->setData(
            [
                'page' => 5,
                'page_size' => 25,
            ]
        );

        $response = $clientCN->getAll($entity);

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
        $clientCNFactory = $clientFactory->create(\Srdorado\SiigoClient\Enum\ClientType::CREDIT_NOTE);
        $clientCN = $clientCNFactory->create();
        $clientCN->setBaseUrl('https://api.siigo.com/');
        $clientCN->setAccessKey($token);

        return $clientCN;
    }

    /**
     * Get token
     *
     * @return string
     * @throws \ReflectionException
     */
    private function getToken()
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
    private function getJson()
    {
        return [
            'document' => [
                'id' => 77775
            ],
            'number' => 22,
            'date' => '2015-12-15',
            'invoice' => '302580df-838b-4531-b8bf-dd3c98b34059',
            'cost_center' => 235,
            'reason' => '1',
            'retentions' => [
                [
                    'id' => 13156
                ]
            ],
            'observations' => 'Observaciones',
            'items' => [
                [
                    'code' => 'Item-1',
                    'description' => 'Camiseta de algodón',
                    'quantity' => 1,
                    'price' => 1069.77,
                    'discount' => 0,
                    'taxes' => [
                        [
                            'id' => 13156
                        ]
                    ]
                ]
            ],
            'payments' => [
                [
                    'id' => 5636,
                  'value' => 1273.03,
                  'due_date' => '2021-03-19'
                ]
            ]
        ];
    }
}
