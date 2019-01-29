<?php

    require 'db/db_connect.php';

    $city = 'Кола';
    $smi_name = 'hibiny.ru';
    global $db;


    $sql = "SELECT * FROM city_tbl";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NUM) )
    {
    print_r($row);
    echo '<br>';
    }

/*
    $sql = "SELECT smi_id FROM smi_tbl WHERE smi_name LIKE '%" . $smi_name. "%'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $smi_id = $stmt->fetch(PDO::FETCH_NUM);
    $smi_id = $smi_id[0];
    echo $smi_id;
*/

/*
    $sql = 'SELECT city_id FROM city_tbl WHERE city_name LIKE "%' . $city. '%"';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM);
    if ($row == FALSE)
        echo 'cvcvcvcv';
    $city_id = $row[0];
    echo $city_id;

*/

?>