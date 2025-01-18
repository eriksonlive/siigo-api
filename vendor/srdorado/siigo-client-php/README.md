# Siigo client API

*By [srdorado](https://github.com/srdorado)*

[![License](https://img.shields.io/packagist/l/srdorado/siigo-client-php)](https://packagist.org/packages/srdorado/siigo-client-php)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/srdorado/siigo-client-php)
[![Packagist Version](https://img.shields.io/packagist/v/srdorado/siigo-client-php)](https://packagist.org/packages/srdorado/siigo-client-php)
[![Packagist Downloads](https://img.shields.io/packagist/dt/srdorado/siigo-client-php)](https://packagist.org/packages/srdorado/siigo-client-php)
[![Composer dependencies](https://img.shields.io/badge/dependencies-0-brightgreen)](https://github.com/srdorado/siigo-client-php/blob/master/composer.json)
[![Test workflow](https://img.shields.io/github/workflow/status/srdorado/siigo-client-php/test?label=test&logo=github)](https://github.com/srdorado/siigo-client-php/actions?workflow=test)
[![Codecov](https://img.shields.io/codecov/c/github/srdorado/siigo-client-php?logo=codecov)](https://codecov.io/gh/srdorado/siigo-client-php)
[![composer.lock](https://poser.pugx.org/srdorado/siigo-client-php/composerlock)](https://packagist.org/packages/srdorado/siigo-client-php)


This library consumes the 
[Siigo API](https://siigoapi.docs.apiary.io/#) enabling: 

* Create, update and consult Sales Invoices.

* Sending sales invoices to the Dian and customers.

* Create, consult, update and delete Clients (Third Parties) and Products/Services.

* Create and consult Accounting Receipts, Credit Notes and Cash Receipts.

* Consult Inventory of a product in Siigo Cloud.

## Installation
```php
composer require srdorado/siigo-client-php
```

## How to use it?

* Get token

```php
    function getToken()
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
```

* Create product

```php
    function getCustomClient()
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



    function create()
    {
        $clientProduct = $this->getCustomClient();

        $entity = new \Srdorado\SiigoClient\Model\Entity(\Srdorado\SiigoClient\Enum\ClientType::PRODUCT);

        $dataEntity = $this->getExampleCompleteProduct();

        $entity->setData($dataEntity);

        $productId = $clientProduct->create($entity);

        $this->assertTrue(true);
    }
```

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.

## Donations

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.com/paypalme/srdorado01)
