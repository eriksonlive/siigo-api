<?php

require_once __DIR__ . '/bootstrap.php';

use App\Controller\InvoiceController;
use App\Invoice\InvoiceMappedJson;
use App\Querys\QueryHandler;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;

session_start();

function crearFactura()
{
    $invoiceMapped = new InvoiceMappedJson();
    $siigo = new InvoiceController();
    $token = $siigo->getToken();
    $idFactura = 6472;

    try {

        //! Obtiene la info de la base de datos
        $queryHandler = new QueryHandler();
        $data = $queryHandler->getInvoice($idFactura);

        // echo '<pre>';
        // print_r($data);
        // echo '</pre>';
        // die();

        $data[0]['num_factura'] = '28239';
        $data[0]['nit_sin_df'] = '8300033684';
        $data[0]['direccion'] = 'CRA 13A 77A 64';
        $data[0]['correodestinatario'] = 'gramirez@pruebas.com.co';
        $data[0]['vendedor'] = '62';
        $data[0]['codigo_producto'] = '070273-17923';
        $data[0]['declara_iva'] = '19203';
        $data[0]['id_medio_pago'] = '8113';
        $data[0]['valor_pago'] = '71400';

        //! Mapea la informacion para procesarla
        $mapInvoice = $invoiceMapped->createJson($data);

        // echo '<pre>';
        // print_r($mapInvoice);
        // echo '</pre>';
        // die();

        //! Crea la factura en base a los datos procesados anteriormente
        $response = $siigo->createInvoice($token, $mapInvoice);

        //! Obtiene la informacion desde siigo por su id
        // $list = $siigo->getInvoiceById($token, '69d75011-acdc-4c05-a84f-61f302341b2f');

        $_SESSION['response'] = $response;
        header("Location: ./?success=1");
    } catch (PDOException $e) {
        echo "Error al ejecutar la consulta: " . $e->getMessage();
        $_SESSION['response'] = $e->getMessage();
        header("Location: ./?success=0");
    } catch (ClientException | ServerException $e) {
        echo "Error de cliente o servidor: " . $e->getMessage();
        $_SESSION['response'] = $e->getMessage();
        header("Location: ./?success=0");
    } catch (Exception $e) {
        echo "Error inesperado: " . $e->getMessage();
        $_SESSION['response'] = $e->getMessage();
        header("Location: ./?success=0");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    crearFactura();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="" method="post">
        <button type="submit">Crear factura</button>
    </form>

    <?php
    if (isset($_GET['success']) && $_GET['success'] == 1 && isset($_SESSION['response'])) {
        echo "<p style='color: green;'>Factura creada correctamente.</p>";
        echo "<pre>" . print_r($_SESSION['response'], true) . "</pre>";
        unset($_SESSION['response']);
    } else if (isset($_GET['success']) && $_GET['success'] == 0 && isset($_SESSION['response'])) {
        echo "<p style='color: red;'>Error al procesar los datos</p>";
        echo "<pre>" . print_r($_SESSION['response'], true) . "</pre>";
        unset($_SESSION['response']);
    }
    ?>

</body>

</html>