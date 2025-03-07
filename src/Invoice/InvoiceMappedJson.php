<?php

namespace App\Invoice;

use App\Options\ErrorsDump;

class InvoiceMappedJson
{
    public function createJson($data, $dataOptions = [])
    {
        $result = $this->formatData($data, $dataOptions);
        // Convertir resultado a un array indexado
        $result = array_values($result);

        $items = [];
        $payments = [];

        foreach ($result[0]['items'] as $cl => $vl) {
            $items[$cl] = [
                "code" => $vl['codigo_producto'],
                "description" => $vl['producto'],
                "quantity" => (int) $vl['cantidad'],
                "price" => (float) $vl['precio'],
                "discount" => (float) $vl['descuento'],
                // "taxes" => [
                //     [
                //         "id" => (int) $vl['declara_iva']
                //     ]
                // ]
            ];
        }

        foreach ($result[0]['methods_pay'] as $cl => $vl) {
            $payments[$cl] = [
                "id" => (int) $vl['id_medio_pago'],
                "value" => (float) $vl['valor_pago'],
                "due_date" => $vl['fecha_pago']
            ];
        }

        $mappedData = [
            "document" => [
                "id" => $result[0]['type_fact'], // Usamos 'num_factura' como ID
            ],
            "date" => date("Y-m-d"), // Fecha actual
            "number" => $result[0]['num_fac'],
            "customer" => [
                "person_type" => $result[0]['tipo_persona'] === 'Natural' ? "Person" : "Company",
                "id_type" => $result[0]['tipo_persona'] === 'Natural' ? "13" : "31", // Código ficticio para tipo de identificación (modifícalo según tu lógica)
                "identification" => $result[0]['nit_sin_df'],
                "branch_office" => "0",
                "name" => $result[0]['tipo_persona'] === 'Natural' ? explode(" ", $data[0]['razonsocial'], 2) : [$data[0]['razonsocial']], // Divide nombre en partes
                "address" => [
                    "address" => $result[0]['direccion'],
                    "city" => [
                        "country_code" => $result[0]['codigo_pais'],
                        "state_code" => $result[0]['codigo_departamento'],
                        "city_code" => $result[0]['codigo_ciudad']
                    ]
                ],
                "phones" => [
                    [
                        "number" => $result[0]['telefonodestinatario']
                    ]
                ],
                "contacts" => [
                    [
                        "first_name" => "Manuel",
                        "last_name" => "Camacho",
                        "email" => $result[0]['correodestinatario']
                    ]
                ]
            ],
            "seller" => (int) $result[0]['vendedor'], // Convertimos a entero
            "stamp" => [
                "send" => false
            ],
            "mail" => [
                "send" => true
            ],
            "observations" => $result[0]['notas'] ?? "Sin observaciones",
            "items" => $items,
            "payments" => $payments
        ];

        // !Este codigo transforma el mappedData en Json, con esot verificamos la estructura final
        // $jsonData = json_encode($mappedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // ErrorsDump::errors($jsonData, 'print', true);

        return $mappedData;
    }

    public function formatData($data, $dataOptions = [])
    {
        $result = [];

        // Agrupar datos
        foreach ($data as $item) {
            $num_factura = $item['num_factura'];

            if (count($dataOptions) > 0) {
                foreach ($dataOptions as $cl => $vl) {
                    $item[$cl] = $vl;
                }
            }

            if (preg_match('/^([A-Za-z]+)(\d+)$/', $num_factura, $coincidencias)) {
                $letras  = $coincidencias[1]; // Primera parte: letras
                $numeros = $coincidencias[2]; // Segunda parte: números

                // echo "Letras: $letras\n";
                // echo "Números: $numeros\n";
            } else {
                echo "El formato de la cadena no coincide con el patrón esperado.\n";
            }

            // Si la factura aún no está en el array, se agrega con los datos generales
            if (!isset($result[$num_factura])) {
                $result[$num_factura] = [
                    "department_id" => $item["department_id"],
                    // "num_factura" => $item["num_factura"],
                    "type_fact" => $item["type_fact"],
                    "num_fac" => $numeros,
                    // "num_fac" => $numeros,
                    "fecha_factura" => $item["fecha_factura"],
                    "tipo_persona" => $item["tipo_persona"],
                    "nit_sin_df" => $item["nit_sin_df"],
                    "digitoverificacion" => $item["digitoverificacion"],
                    "razonsocial" => $item["razonsocial"],
                    "direccion" => $item["direccion"],
                    "codigo_pais" => $item["codigo_pais"],
                    "nombre_pais" => $item["nombre_pais"],
                    "codigo_departamento" => $item["codigo_departamento"],
                    "nombre_departamento" => $item["nombre_departamento"],
                    "codigo_ciudad" => $item["codigo_ciudad"],
                    "nombre_ciudad" => $item["nombre_ciudad"],
                    "codpostal" => $item["codpostal"],
                    "indicativo" => $item["indicativo"],
                    "telefonodestinatario" => $item["telefonodestinatario"],
                    "correodestinatario" => $item["correodestinatario"],
                    "vendedor" => $item["vendedor"],
                    "notas" => $item["notas"],
                    "items" => [], // Inicializamos un array vacío para los items
                    "methods_pay" => [] // Inicializamos un array vacío para los métodos de pago
                ];

                // Reiniciar el total de la factura para evitar acumulaciones incorrectas
                $result[$num_factura]['total_factura'] = 0;
            }

            // Calcular el subtotal del producto
            $subtotalProducto = ($item['cantidad'] * $item['precio']) - $item['descuento'];

            // Sumar el total de la factura correctamente
            $result[$num_factura]['total_factura'] += $subtotalProducto;

            // Agregar los datos del producto al array de items
            $result[$num_factura]["items"][] = [
                "codigo_producto" => $item["codigo_producto"],
                "producto" => $item["producto"],
                "cantidad" => $item["cantidad"],
                "precio" => $item["precio"],
                "descuento" => $item["descuento"],
                "declara_iva" => $item["declara_iva"]
            ];

            // Unificar métodos de pago
            $existingMethodIndex = null;
            foreach ($result[$num_factura]['methods_pay'] as $index => $method) {
                if ($method['id_medio_pago'] === $item['id_medio_pago']) {
                    $existingMethodIndex = $index;
                    break;
                }
            }

            if ($existingMethodIndex !== null) {
                // Si ya existe el método de pago, actualizamos con el valor total de la factura
                $result[$num_factura]['methods_pay'][$existingMethodIndex]['valor_pago'] = $result[$num_factura]['total_factura'];
            } else {
                // Si no existe, lo agregamos como uno nuevo con el valor total de la factura
                $result[$num_factura]['methods_pay'][] = [
                    "id_medio_pago" => $item["id_medio_pago"],
                    "valor_pago" => $result[$num_factura]['total_factura'],
                    "fecha_pago" => $item["fecha_pago"]
                ];
            }
        }

        return $result;
    }
}
