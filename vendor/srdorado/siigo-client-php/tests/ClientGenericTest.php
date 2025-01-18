<?php

namespace Srdorado\SiigoClient\Tests;

use PHPUnit\Framework\TestCase;

class ClientGenericTest extends TestCase
{
    /**
     * @test
     */
    public function getTaxes()
    {
        $clientGeneric = $this->getCustomClient();

        $result = $clientGeneric->getTaxes();

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function getDocumentTypes()
    {

        $clientGeneric = $this->getCustomClient();

        // Create entity token
        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::TOKEN);
        $entity->setData(
            [
                'type' => 'FV'
            ]
        );

        $result = $clientGeneric->getDocumentTypes($entity);

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
        $clientProductFactory = $clientFactory->create(\Srdorado\SiigoClient\Enum\ClientType::GENERIC);
        $clientGeneric = $clientProductFactory->create();
        $clientGeneric->setBaseUrl('https://api.siigo.com/');
        $clientGeneric->setAccessKey($token);

        return $clientGeneric;
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
}
