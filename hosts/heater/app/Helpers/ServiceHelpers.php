<?php

namespace App\Helpers;

class ServiceHelpers
{
    public function debughtml($data = [])
    {
        echo '<pre>';
        foreach ($data as $key => $value) {
            echo 'Variable: ' . $key . '<br/>';
            echo 'Value: ';
            print_r($value);
            echo '<br/>';
        }
        echo '</pre>';
    }
}
