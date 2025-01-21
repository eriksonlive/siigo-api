<?php

namespace App\Invoice;

use stdClass;

class InvoiceMappedJson
{
    public function createJson($data = [])
    {
        $mappedData = [
            "document" => [
                "id" => $data[0]['num_factura'] // Usamos 'num_factura' como ID
            ],
            "date" => date("Y-m-d"), // Fecha actual
            "customer" => [
                "person_type" => $data[0]['tipo_persona'] === 'Natural' ? "Person" : "Company",
                "id_type" => "13", // Código ficticio para tipo de identificación (modifícalo según tu lógica)
                "identification" => $data[0]['nit_sin_df'],
                "branch_office" => 0,
                "name" => explode(" ", $data[0]['razonsocial'], 2), // Divide nombre en partes
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
            "items" => [
                [
                    "code" => $data[0]['codigo_producto'],
                    "description" => $data[0]['producto'],
                    "quantity" => (int) $data[0]['cantidad'],
                    "price" => (float) $data[0]['precio'],
                    "discount" => (float) $data[0]['descuento'],
                    "taxes" => [
                        [
                            "id" => (int) $data[0]['declara_iva']
                        ]
                    ]
                ]
            ],
            "payments" => [
                [
                    "id" => (int) $data[0]['id_medio_pago'],
                    "value" => (float) $data[0]['valor_pago'],
                    "due_date" => $data[0]['fecha_pago']
                ]
            ],
            "additional_fields" => new stdClass() // Objeto vacío
        ];

        // $jsonData = json_encode($mappedData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return $mappedData;
    }
}
