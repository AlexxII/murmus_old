<?php

require('db/db_connect.php');

function GetCityIdFromDB ($city){

    global $db;

    $sql = 'SELECT city_id FROM city_tbl WHERE city_name = :param1';
    $stmt = $db->prepare($sql);
    $stmt->execute(array('param1' => $city));
    $row = $stmt->fetch(PDO::FETCH_NUM);
    if ($row == FALSE)
        return 999;
    $smi_id = $row[0];
    return $smi_id;
}
?>