<?php

namespace Srdorado\SiigoClient\Tests;

use PHPUnit\Framework\TestCase;

class ClientProductTest extends TestCase
{
    /**
     * @test
     */
    public function create()
    {
        $clientProduct = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);

        $dataEntity = $this->getExampleCompleteProduct();

        $entity->setData($dataEntity);

        $productId = $clientProduct->create($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getAll()
    {
        $clientProduct = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);

        $entity->setData(
            [
            'page' => 1,
            'page_size' => 25,
            ]
        );

        $products = $clientProduct->getAll($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getByCreatedStart()
    {
        $clientProduct = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);

        $entity->setData(
            [
                'created_start' => '1995-12-12',
                'page' => 1,
                'page_size' => 25,
            ]
        );

        $products = $clientProduct->getByCreatedStart($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getById()
    {
        $clientProduct = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);

        $entity->setData(
            [
                'product_id' => 'c1c68056-8307-4b0c-a678-4b66485f85b3'
            ]
        );

        $productId = $clientProduct->getById($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getByCode()
    {
        $clientProduct = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);

        $entity->setData(
            [
                'code' => 'Code-20001'
            ]
        );

        $productId = $clientProduct->getByCode($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function update()
    {
        $clientProduct = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);

        $dataEntity = $this->getExampleBasicProduct();

        $dataEntity = array_merge(
            $dataEntity,
            [
                'url_request' => [
                    'product_id' => 'd9860e2b-899b-4100-8d66-64eea16dce7d'
                ]
            ]
        );

        $entity->setData($dataEntity);

        $productId = $clientProduct->update($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function delete()
    {
        $clientProduct = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);

        $entity->setData(
            [
                'product_id' => '5700d7de-3f59-4ce5-93ac-c3a555e870e0'
            ]
        );

        $result = $clientProduct->delete($entity);

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getAccountGroups()
    {
        $clientProduct = $this->getCustomClient();

        $result = $clientProduct->getAccountGroups();

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getWareHouses()
    {
        $clientProduct = $this->getCustomClient();

        $result = $clientProduct->getWareHouses();

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
        $clientProductFactory = $clientFactory->create(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);
        $clientProduct = $clientProductFactory->create();
        $clientProduct->setBaseUrl('https://api.siigo.com/');
        $clientProduct->setAccessKey($token);


        return $clientProduct;
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
     * Get data example basic product
     *
     * @return array
     */
    private function getExampleBasicProduct()
    {
        return [
            'code' => 'Code-20001',
            'name' => 'Producto de prueba 20001 - update',
            'account_group' => 1253
        ];
    }

    /**
     * Get data example complete product
     *
     * @return array
     */
    private function getExampleCompleteProduct()
    {
        return [
            'code' => 'Code-20009999',
            'name' => 'Camiseta de algodón 20004',
            'account_group' => 1253,
            'type' => 'Product',
            'stock_control' => false,
            'active' => true,
            'tax_classification' => 'Taxed',
            'tax_included' => false,
            'tax_consumption_value' => 0,
            'taxes' => [
                [
                    'id' => 13156
                ],
                [
                     'id' => 13158
                ]
            ],
            'prices' => [
                [
                    'currency_code' => 'COP',
                    'price_list' => [
                        [
                            'position' => 1,
                            'value' => 12000
                        ]
                    ]
                ]
            ],
            'unit' => [
                'code' => '94',
                'name' => 'unidad',
            ],
            'unit_label' => 'unidad',
            'reference' => 'REF1',
            'description' => 'Camiseta de algodón blanca',
            'additional_fields' => [
                'barcode' => 'B0123',
                'brand' => 'Gef',
                'tariff' => '151612',
                'model' => 'Loiry'
            ],
            'available_quantity' => 1,
            'warehouses' => [1234]
        ];
    }
}
