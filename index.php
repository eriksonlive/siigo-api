<?php

require 'vendor/autoload.php';

use Srdorado\SiigoClient\Factory\ClientFactory;
use Srdorado\SiigoClient\Enum\ClientType;
use Srdorado\SiigoClient\Model\Entity;

function getToken()
{
    // Crear el cliente para obtener el token
    $clientFactory = new ClientFactory();
    $clientTokenFactory = $clientFactory->create(ClientType::TOKEN);
    $clientToken = $clientTokenFactory->create();
    $clientToken->setBaseUrl('https://api.siigo.com/');

    // Crear la entidad con las credenciales
    $entity = new Entity(ClientType::TOKEN);
    $entity->setData([
        'username' => 'sandbox@siigoapi.com',
        'access_key' => 'NDllMzI0NmEtNjExZC00NGM3LWE3OTQtMWUyNTNlZWU0ZTM0OkosU2MwLD4xQ08='
    ]);

    // Solicitar el token
    $response = $clientToken->getToken($entity);

    return $response;
}

function getInvoices($token)
{
    // Crear el cliente de facturas
    $clientFactory = new ClientFactory();
    $clientInvoiceFactory = $clientFactory->create(ClientType::INVOICE);
    $clientInvoice = $clientInvoiceFactory->create();
    $clientInvoice->setBaseUrl('https://api.siigo.com/');

    $clientInvoice->setAccessToken($token);
    $clientInvoice->setScope('SGM');

    $entity = new Entity(ClientType::INVOICE);
    $entity->setData([
        'page' => 1,
        'page_size' => 5
    ]);

    $response = $clientInvoice->getAll($entity);

    return $response;
}

function getInvoiceById($token)
{
    // Crear el cliente de facturas
    $clientFactory = new ClientFactory();
    $clientInvoiceFactory = $clientFactory->create(ClientType::INVOICE);
    $clientInvoice = $clientInvoiceFactory->create();
    $clientInvoice->setBaseUrl('https://api.siigo.com/');

    $clientInvoice->setAccessToken($token);
    $clientInvoice->setScope('SGM');

    $entity = new Entity(ClientType::INVOICE);
    $entity->setData(['af92bcd0-438a-410f-a9c2-ad3ccf0661cd']);

    $response = $clientInvoice->getById($entity);

    return $response;
}

function createInvoice($token)
{
    $clientFactory = new ClientFactory();
    $clientInvoiceFactory = $clientFactory->create(ClientType::INVOICE);
    $clientInvoice = $clientInvoiceFactory->create();
    $clientInvoice->setBaseUrl('https://api.siigo.com/');

    $clientInvoice->setAccessToken($token);
    $clientInvoice->setScope('SGM');

    $invoiceData = [
        "document" => [
            "id" => "28239"
        ],
        "date" => "2025-01-18",
        "customer" => [
            "person_type" => "Person",
            "id_type" => "13",
            "identification" => "1020477",
            "branch_office" => 0,
            "name" => ["Lorem", "ipsum"],
            "address" => [
                "address" => "Cra. 18 #79A - 42",
                "city" => [
                    "country_code" => "Co",
                    "country_name" => "Colombia",
                    "state_code" => "19",
                    "state_name" => "Antioquia",
                    "city_code" => "19001",
                    "city_name" => "Medellin"
                ],
                "postal_code" => "110911"
            ],
            "phones" => [
                [
                    "indicative" => "57",
                    "number" => "3006003345",
                    "extension" => "132"
                ]
            ],
            "contacts" => [
                [
                    "first_name" => "Marcos",
                    "last_name" => "Castillo",
                    "email" => "marcos.castillo@contacto.com",
                    "phone" => [
                        "indicative" => "57",
                        "number" => "3006003345",
                        "extension" => "132"
                    ]
                ]
            ]
        ],
        "currency" => [
            "code" => "USD",
            "exchange_rate" => 3825.03
        ],
        "seller" => 62,
        "stamp" => [
            "send" => true
        ],
        "mail" => [
            "send" => true
        ],
        "observations" => "Observaciones",
        "items" => [
            [
                "code" => "Item-1",
                "description" => "Camiseta de algodón",
                "quantity" => 1,
                "price" => 1069.77,
                "discount" => 0.0,
                "taxes" => [
                    [
                        "id" => 19187
                    ]
                ]
            ]
        ],
        "payments" => [
            [
                "id" => 8113,
                "value" => 1176.75,
                "due_date" => "2021-03-19"
            ]
        ],
        "globaldiscounts" => [
            [
                "id" => 13156,
                "percentage" => 10.00,
                "value" => 100.0
            ]
        ]
    ];

    $entity = new Entity(ClientType::INVOICE);
    $entity->setData($invoiceData);

    $response = $clientInvoice->create($entity);

    // Verificar si la respuesta es exitosa
    if ($response) {
        echo "Factura creada correctamente:\n";
        // print_r($response);  // Muestra los detalles de la factura creada
    } else {
        echo "Error al crear la factura.\n";
    }

    return $response;
}

// Obtener el token
$token = getToken();
if ($token) {
    // echo "Token obtenido correctamente.\n";

    $lorem = getInvoice($token);
    var_dump($lorem);

    // $result = createInvoice($token);
    // var_dump($result);

    // Crear un producto
    // $product = createProduct($token);
    // echo "Producto creado:\n";
    // print_r($product);
} else {
    echo "Error al obtener el token.\n";
}
