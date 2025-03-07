<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controller\InvoiceController;
use App\Invoice\InvoiceMappedJson;
use App\Options\PrintDump;
use App\Querys\QueryHandler;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

function crearFactura()
{
    $invoiceMapped = new InvoiceMappedJson();
    $siigo = new InvoiceController();
    $queryHandler = new QueryHandler();

    //! Obtiene la info de la base de datos
    $token = $siigo->getToken();
    $idFactura = "6245";
    $idFac = "6330";

    $data = $queryHandler->getInvoice($idFactura);
    $pendingErrors = $queryHandler->getErrorInvoices();

    //! PrintDump::print_dump($data, 'print');

    if (count($pendingErrors) > 0) {
        $res = $pendingErrors;
    } else {
        $queryHandler->setArIntegrationErp('range', $idFactura, $idFac);
        $res = $queryHandler->proccessErp(2);
    }

    foreach ($res as $register) {
        $idRegister = $register['id'];
        $arId = $register['ar_id'];

        $queryHandler->setStatusErpProccess($idRegister);

        try {

            $data = $queryHandler->getInvoice($arId);

            $options = [
                "type_fact" => "29193",
                // "num_fac" => "4832",
                "vendedor" => '856',
                "codigo_producto" => "954105",
                // "declara_iva" => "12766",
                "id_medio_pago" => '9439'
            ];

            //! Mapea la informacion para procesarla
            $mapInvoice = $invoiceMapped->createJson($data, $options);

            //! Crea la factura en base a los datos procesados anteriormente
            $response = $siigo->createInvoice($token, $mapInvoice);

            //? Obtiene la informacion desde siigo por su id
            // $list = $siigo->getInvoiceById($token, '69d75011-acdc-4c05-a84f-61f302341b2f');

            $queryHandler->setStatusErpOk($response, $idRegister);
        } catch (PDOException $e) {
            $queryHandler->setStatusErpError($e, $idRegister);
        } catch (ClientException | ServerException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            PrintDump::print_dump($responseBody, 'dump');
            $queryHandler->setStatusErpError($responseBody, $idRegister);
        } catch (Exception $e) {
            $queryHandler->setStatusErpError($e, $idRegister);
        }
    }

    sleep(5);
}

crearFactura();
