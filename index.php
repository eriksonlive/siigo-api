<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controller\InvoiceController;
use App\Invoice\InvoiceMappedJson;
use App\Querys\QueryHandler;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

// session_start();

function crearFactura()
{
    $invoiceMapped = new InvoiceMappedJson();
    $siigo = new InvoiceController();
    //! Obtiene la info de la base de datos
    $queryHandler = new QueryHandler();
    $token = $siigo->getToken();
    $idFactura = 6472;
    $pausa = 2;

    // while (true) {

    // $res = $queryHandler->getArIntegrationErp();

    // if ($res['total'] === 0) {
    // $res = $queryHandler->setArIntegrationErp();
    // }

    // $res = $queryHandler->proccessErp(10);

    $erroresPendientes = $queryHandler->getErrorInvoices();
    // Suponemos que getErrorInvoices() ejecuta algo como:
    // SELECT * FROM public.ar_integration_erp 
    // WHERE Integration_status = 'error' AND reintentos < 3

    if (count($erroresPendientes) > 0) {
        // Si existen facturas en error, se procesan esas
        $res = $erroresPendientes;
        echo "Procesando facturas en error...\n";
    } else {
        // Si no hay facturas en error, cargamos nuevos registros desde la tabla principal
        $queryHandler->setArIntegrationErp();
        $res = $queryHandler->proccessErp(5);
        echo "Cargando nuevo lote de facturas...\n";
    }

    // if (!$res) {
    //     echo "No hay facturas pendientes para procesar en ar_integration_erp.\n";
    //     exit;
    // }

    $num = 160;

    foreach ($res as $registro) {
        $idRegistro = $registro['id']; // ID del registro en la tabla de integración
        $arId = $registro['ar_id']; // ID de la factura en la tabla principal

        // Marcar la factura como "procesando" para evitar reprocesos concurrentes
        $queryHandler->setStatusErpProccess($idRegistro);

        try {

            $data = $queryHandler->getInvoice($arId);
            $methodsPay = $queryHandler->getMethodsPay($idRegistro);

            echo '<pre>';
            var_dump($methodsPay);
            echo '</pre>';

            $options = [
                "num_factura"   => ($num == 156) ? "1020" : "29193",
                "nit_sin_df"    => "1020477",
                "num_fac"       => $num,
                "vendedor"      => '851',
                "codigo_producto" => "CCMAWB-32",
                "declara_iva"   => "19228",
                "id_medio_pago" => '9439'
            ];

            //! Mapea la informacion para procesarla
            $mapInvoice = $invoiceMapped->createJson($data, $options);

            //! Crea la factura en base a los datos procesados anteriormente
            $response = $siigo->createInvoice($token, $mapInvoice);

            echo '<pre>';
            var_dump($response);
            echo '</pre>';

            $queryHandler->setStatusErpOk($response, $idRegistro);

            $num++;

            sleep($pausa);
        } catch (PDOException $e) {
            $queryHandler->setStatusErpError($e, $idRegistro);

            var_dump($e);
            $num++;
            // echo "Error al ejecutar la consulta: " . $e->getMessage();
            // $_SESSION['response'] = $e->getMessage();
            // header("Location: ./?success=0");
        } catch (ClientException | ServerException $e) {

            $responseBody = $e->getResponse()->getBody()->getContents();

            var_dump(json_decode($responseBody, true));
            $queryHandler->setStatusErpError($responseBody, $idRegistro);
            $num++;
            // echo "Error de cliente o servidor: " . $e->getMessage();
            // $_SESSION['response'] = $e->getMessage();
            // header("Location: ./?success=0");
        } catch (Exception $e) {

            $queryHandler->setStatusErpError($e, $idRegistro);

            var_dump($e);
            $num++;
            // echo "Error inesperado: " . $e->getMessage();
            // $_SESSION['response'] = $e->getMessage();
            // header("Location: ./?success=0");
        }
        //  finally {
        //     // Esto se ejecuta siempre, haya habido error o no
        //     $num++;           // Se incrementa el contador para que solo la factura con num == 80 falle
        //     sleep($pausa);    // Pausa de 2 segundos entre iteraciones
        // }
    }

    //! Obtiene la informacion desde siigo por su id
    // $list = $siigo->getInvoiceById($token, '69d75011-acdc-4c05-a84f-61f302341b2f');

    // $_SESSION['response'] = $response;
    // header("Location: ./?success=1");


    echo "Paquete actual procesado. Buscando nuevas facturas...\n";

    sleep(10);
}
// }

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     crearFactura();
// }

crearFactura();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="http://localhost/clien-siigo/" method="post">
        <button type="submit">Crear factura</button>
    </form>

    <!-- <?php
            // if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_SESSION['response'])) {
            //     echo "<p style='color: green;'>Factura creada correctamente.</p>";
            //     echo "<pre>" . print_r($_SESSION['response'], true) . "</pre>";
            //     unset($_SESSION['response']);
            // } else if (isset($_GET['success']) && $_GET['success'] == 0 && isset($_SESSION['response'])) {
            //     echo "<p style='color: red;'>Error al procesar los datos</p>";
            //     echo "<pre>" . print_r($_SESSION['response'], true) . "</pre>";
            //     unset($_SESSION['response']);
            // }
            ?> -->

</body>

</html>