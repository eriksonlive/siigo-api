<?php

//? Modos para el manejo de facturas query_mode
// Single: este modo se utiliza cuando se quiere pasar init_id_fac de manera manual uno por uno
// Range: en este modo se manejará un rango de ids, init_id_fac hasta final_id_fac (6245 a 6250) y se procesaran de manera automática
// From: este se utiliza para que a partir del id inicial se procesen las facturas hacia adelante
// List: este modo se maneja haciendo un array de facturas por id [6245, 6324, 6642, ...] para tener un control de lo que se procesa

//? siigo_params: son los diferentes parámetros que se le envían ya sea seteados o de pruebas al documento
//? siigo_conf: son parámetros que se definen en cuanto a rango de numeración y el modo del mismo que se define en siigo

class ConfigGeneral
{
    public function __construct(

        public $queryMode = [
            'mode' => 'single',
            // 'init_id_fac' => [6340, 6341, 6342, 6343, 6244],
            'init_id_fac' => 472935,
            'final_id_fac' => 6777,
            'ciclo' => 10,
            'pausa' => 1
        ],
        public $siigoParams = [
            "type_fact" => "29193",
            // "num_fac" => "4832",
            "vendedor" => '856',
            "codigo_producto" => "954105",
            // "declara_iva" => "12766",
            "id_medio_pago" => '9439'
        ],
        public $siigoConf = [
            'range_numeration_siigo' => [1, 9999999999],
            'num_automatic' => true
        ],
        public $dbHost = "host",
        public $dbName = "db_name",
        public $dbUser = "db_user",
        public $dbPass = "db_pass",

        public $baseUrl = "https://api.siigo.com/",
        public $siigoUsername = "siigo user",
        public $siigoAccessKey = "siigo access key",
        public $scope = "siigo scope"
    ) {}

    public function getQueryMode()
    {
        return $this->queryMode;
    }

    public function getSiigoParams()
    {
        return $this->siigoParams;
    }

    public function getSiigoConf()
    {
        return $this->siigoConf;
    }

    public function getDbHost()
    {
        return $this->dbHost;
    }

    public function getDbName()
    {
        return $this->dbName;
    }

    public function getDbUser()
    {
        return $this->dbUser;
    }

    public function getDbPass()
    {
        return $this->dbPass;
    }

    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function getSiigoUsername()
    {
        return $this->siigoUsername;
    }

    public function getSiigoAccessKey()
    {
        return $this->siigoAccessKey;
    }

    public function getScope()
    {
        return $this->scope;
    }
}
