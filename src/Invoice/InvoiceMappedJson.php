<?php

namespace App\Invoice;

use stdClass;

class InvoiceMappedJson
{
    public function createJson($data = [])
    {
        $data[0]['items'] = [
            [
                'codigo_producto' => $data[0]['codigo_producto'],
                'producto' => $data[0]['producto'],
                'cantidad' => (int) $data[0]['cantidad'],
                'precio' => (float) $data[0]['precio'],
                'descuento' => (float) $data[0]['descuento'],
                'declara_iva' => (int) $data[0]['declara_iva']
            ],
            [
                'codigo_producto' => $data[0]['codigo_producto'],
                'producto' => $data[0]['producto'],
                'cantidad' => (int) $data[0]['cantidad'],
                'precio' => (float) $data[0]['precio'],
                'descuento' => (float) $data[0]['descuento'],
                'declara_iva' => (int) $data[0]['declara_iva']
            ],
        ];

        $data[0]['methods_pay'] = [
            [
                'id_medio_pago' => (int) $data[0]['id_medio_pago'],
                'valor_pago' => (float) $data[0]['valor_pago'],
                'fecha_pago' => $data[0]['fecha_pago']
            ],
        ];

        unset($data[0]['codigo_producto']);
        unset($data[0]['producto']);
        unset($data[0]['cantidad']);
        unset($data[0]['precio']);
        unset($data[0]['descuento']);
        unset($data[0]['declara_iva']);
        unset($data[0]['id_medio_pago']);
        unset($data[0]['valor_pago']);
        unset($data[0]['fecha_pago']);

        $items = [];
        $payments = [];

        foreach ($data[0]['items'] as $cl => $vl) {
            $items[$cl] = [
                "code" => $vl['codigo_producto'],
                "description" => $vl['producto'],
                "quantity" => (int) $vl['cantidad'],
                "price" => (float) $vl['precio'],
                "discount" => (float) $vl['descuento'],
                "taxes" => [
                    [
                        "id" => (int) $vl['declara_iva']
                    ]
                ]
            ];
        }

        foreach ($data[0]['methods_pay'] as $cl => $vl) {
            $payments[$cl] = [
                "id" => (int) $vl['id_medio_pago'],
                "value" => (float) $vl['valor_pago'],
                "due_date" => $vl['fecha_pago']
            ];
        }

        $mappedData = [
            "document" => [
                "id" => $data[0]['num_factura'] // Usamos 'num_factura' como ID
            ],
            "date" => date("Y-m-d"), // Fecha actual
            "customer" => [
                "person_type" => $data[0]['tipo_persona'] === 'Natural' ? "Person" : "Company",
                "id_type" => $data[0]['tipo_persona'] === 'Natural' ? "13" : "31", // Código ficticio para tipo de identificación (modifícalo según tu lógica)
                "identification" => $data[0]['nit_sin_df'],
                "branch_office" => 0,
                "name" => $data[0]['tipo_persona'] === 'Natural' ? explode(" ", $data[0]['razonsocial'], 2) : $data[0]['razonsocial'], // Divide nombre en partes
                "address" => [
                    "address" => $data[0]['direccion'],
                    "city" => [
                        "country_code" => $data[0]['codigo_pais'],
                        "country_name" => $data[0]['nombre_pais'],
                        "state_code" => $data[0]['codigo_departamento'],
                        "state_name" => $data[0]['nombre_departamento'],
                        "city_code" => $data[0]['codigo_ciudad'],
                        "city_name" => $data[0]['nombre_ciudad']
                    ],
                    "postal_code" => $data[0]['codpostal'] ?? ""
                ],
                "phones" => [
                    [
                        "indicative" => $data[0]['indicativo'] ?? "57",
                        "number" => $data[0]['telefonodestinatario']
                    ]
                ],
                "contacts" => [
                    [
                        "email" => $data[0]['correodestinatario']
                    ]
                ]
            ],
            "seller" => (int) $data[0]['vendedor'], // Convertimos a entero
            "stamp" => [
                "send" => true
            ],
            "mail" => [
                "send" => true
            ],
            "observations" => $data[0]['notas'] ?? "Sin observaciones",
            "items" => $items,
            "payments" => $payments,
            "additional_fields" => new stdClass() // Objeto vacío
        ];

        // $jsonData = json_encode($mappedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // echo '<pre>';
        // print_r($jsonData);
        // echo '</pre>';
        // die();

        return $mappedData;
    }
}
