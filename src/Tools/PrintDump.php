<?php

namespace App\Tools;

class PrintDump
{
    public static function print_dump($error, $method = '', $die = false)
    {
        if ($method == 'print') {
            echo '<pre>';
            \print_r($error);
            echo '</pre>';
        } else {
            echo '<pre>';
            \var_dump($error);
            echo '</pre>';
        }

        if ($die) {
            die();
        }
    }
}
