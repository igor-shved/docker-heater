<?php function lockFileAndGetContents($file)
{
    $res = "error";

    $fstate = fopen($file, "r+");
    if (flock($fstate, LOCK_EX)) // установка исключительной блокировки на запись
    {
        $res = fread($fstate, filesize($file));
        if ($res === FALSE)
            $res = "error";

        flock($fstate, LOCK_UN); // снятие блокировки
        fclose($fstate);
    } else
        fclose($fstate);

    return htmlspecialchars($res);
}

function countVal($val)
{
    $retVal = 0;
    if (!is_null($val)) {
        $retVal = 1;
        if (is_countable($val)) {
            $retVal = count($val);
        }
        return $retVal;
    }
}

function debughtml($data = [])
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


?>