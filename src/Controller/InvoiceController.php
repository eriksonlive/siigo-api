<?php

namespace App\Controller;

use App\Options\PrintDump;
use Srdorado\SiigoClient\Enum\ClientType;
use Srdorado\SiigoClient\Factory\ClientFactory;
use Srdorado\SiigoClient\Model\Entity;

class InvoiceController
{
    public $base_url;
    public $siigo_user;
    public $siigo_key;
    public $scope;

    public function __construct()
    {
        $this->base_url = $_ENV['BASE_URL'];
        $this->siigo_user = $_ENV['SIIGO_USERNAME'];
        $this->siigo_key = $_ENV['SIIGO_ACCESS_KEY'];
        $this->scope = $_ENV['SCOPE'];
    }

    public function getToken()
    {
        // Crear el cliente para obtener el token
        $clientFactory = new ClientFactory();
        $clientTokenFactory = $clientFactory->create(ClientType::TOKEN);
        $clientToken = $clientTokenFactory->create();
        $clientToken->setBaseUrl($this->base_url);

        // Crear la entidad con las credenciales
        $entity = new Entity(ClientType::TOKEN);
        $entity->setData([
            'username' => $this->siigo_user,
            'access_key' => $this->siigo_key
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
        $clientInvoice->setBaseUrl($this->base_url);

        $clientInvoice->setAccessToken($token);
        $clientInvoice->setScope($this->scope);

        $entity = new Entity(ClientType::INVOICE);
        $entity->setData([
            'page' => 1,
            'page_size' => 5
        ]);

        $response = $clientInvoice->getAll($entity);

        return $response;
    }

    function getInvoiceById($token, $id_invoice)
    {
        // Crear el cliente de facturas
        $clientFactory = new ClientFactory();
        $clientInvoiceFactory = $clientFactory->create(ClientType::INVOICE);
        $clientInvoice = $clientInvoiceFactory->create();
        $clientInvoice->setBaseUrl($this->base_url);

        $clientInvoice->setAccessToken($token);
        $clientInvoice->setScope($this->scope);

        $entity = new Entity(ClientType::INVOICE);
        $entity->setData([$id_invoice]);

        $response = $clientInvoice->getById($entity);

        return $response;
    }

    function createInvoice($token, $invoiceData)
    {
        $clientFactory = new ClientFactory();
        $clientInvoiceFactory = $clientFactory->create(ClientType::INVOICE);
        $clientInvoice = $clientInvoiceFactory->create();
        $clientInvoice->setBaseUrl($this->base_url);

        $clientInvoice->setAccessToken($token);
        $clientInvoice->setScope($this->scope);

        $entity = new Entity(ClientType::INVOICE);
        $entity->setData($invoiceData);

        $response = $clientInvoice->create($entity);

        // Verificar si la respuesta es exitosa
        // if ($response) {
        //     echo "Factura creada correctamente:\n";
        //     PrintDump::print_dump($response, 'print');
        //     // print_r($response);  // Muestra los detalles de la factura creada
        // } else {
        //     echo "Error al crear la factura.\n";
        // }

        return $response;
    }
}
