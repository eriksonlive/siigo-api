<?php

require_once __DIR__ . '/bootstrap.php';
$config = require_once __DIR__ . '/config.php';

use App\Controller\InvoiceController;
use App\Invoice\InvoiceMappedJson;
use App\Options\PrintDump;
use App\Querys\QueryHandler;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

function crearFactura($config)
{
    $invoiceMapped = new InvoiceMappedJson();
    $siigo = new InvoiceController();
    $queryHandler = new QueryHandler();

    //! Obtiene la info de la base de datos
    $token = $siigo->getToken();
    $mode = $config['query_mode']['mode'];
    $initId = $config['query_mode']['init_id_fac'];
    $finalId = $config['query_mode']['final_id_fac'];
    $ciclo = $config['query_mode']['ciclo'];
    $pausa = $config['query_mode']['pausa'];

    $data = $queryHandler->getInvoice($initId);
    $pendingErrors = $queryHandler->getErrorInvoices();

    if (\count($pendingErrors) > 0) {
        $res = $pendingErrors;
    } else {
        $queryHandler->setArIntegrationErp($mode, $initId, $finalId);
        $res = $queryHandler->proccessErp($ciclo);
    }

    foreach ($res as $register) {
        $idRegister = $register['id'];
        $arId = $register['ar_id'];

        $queryHandler->setStatusErpProccess($idRegister);

        try {
            $data = $queryHandler->getInvoice($arId);

            //! Mapea la informacion para procesarla
            $mapInvoice = $invoiceMapped->createJson($data, $config['siigo_params']);

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

    sleep($pausa);
}

crearFactura($config);
