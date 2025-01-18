<?php

require 'vendor/autoload.php';

if (class_exists('Srdorado\SiigoClient\SiigoClient')) {
    echo "Clase cargada correctamente.";
} else {
    echo "Error: Clase 'Srdorado\\SiigoClient\\SiigoClient' no encontrada.";
}
