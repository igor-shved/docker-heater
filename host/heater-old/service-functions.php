<?php
function debughtml($data = [], $die = false)
{
    echo '<pre>';
    foreach ($data as $key => $value) {
        echo 'Variable: ' . $key . '<br/>';
        echo 'Value: ';
        print_r($value);
        echo '<br/>';
    }
    echo '</pre>';
    if ($die) {
        die();
    }
}

function savelog($data = [], $file_name = '')
{
    if ($data == []) {
        return;
    }
    if ($file_name == '') {
        $file_name = date('d_m_Y') . '.log';
    }
    $path_file = dirname(__DIR__, 1) . '/logs/' . $file_name;
    //debughtml(['$path_file' =>$path_file], true);
    $fw = fopen($path_file, "a+");
    fwrite($fw, date('d.m.Y H:i:s') . ' - ' . json_encode($data) . PHP_EOL);
    fclose($fw);
}

?>