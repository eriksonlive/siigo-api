<?php

//? Modos para el manejo de facturas query_mode
// Single: este modo se utiliza cuando se quiere pasar init_id_fac de manera manual uno por uno
// Range: en este modo se manejará un rango de ids, init_id_fac hasta final_id_fac (6245 a 6250) y se procesaran de manera automática
// From: este se utiliza para que a partir del id inicial se procesen las facturas hacia adelante
// List: este modo se maneja haciendo un array de facturas por id [6245, 6324, 6642, ...] para tener un control de lo que se procesa

return [
    'query_mode' => [
        'mode' => 'single',
        'init_id_fac' => 6245,
        'final_id_fac' => 6250,
        'ciclo' => 5,
        'pausa' => 2
    ],
    'siigo_params' => [
        "type_fact" => "29193",
        // "num_fac" => "4832",
        "vendedor" => '856',
        "codigo_producto" => "954105",
        // "declara_iva" => "12766",
        "id_medio_pago" => '9439'
    ]
];
