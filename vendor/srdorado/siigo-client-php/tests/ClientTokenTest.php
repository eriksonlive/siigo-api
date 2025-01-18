<?php

namespace Srdorado\SiigoClient\Tests;

use PHPUnit\Framework\TestCase;

class ClientTokenTest extends TestCase
{
    /**
     * @test
     */
    public function getTokenTest()
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
        $token = $clientToken->getToken($entity);

        $this->assertTrue(true);
    }
}
