<?php

require('db/db_connect.php');

function GetDBArray($smi_name)
{
    global $db;

    $sql = "SELECT smi_id FROM smi_tbl WHERE smi_name LIKE '%" . $smi_name. "%'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $smi_id = $stmt->fetch(PDO::FETCH_NUM);
    $smi_id = $smi_id[0];


    $sql = "SELECT news_url FROM news_tbl, smi_tbl WHERE smi_name LIKE '%" . $smi_name ."%' and smi_tbl.smi_id =" . $smi_id;
    $stmt = $db->prepare( $sql );
    $stmt->execute();
    $r[] = 0;
    while ($row = $stmt->fetch(PDO::FETCH_NUM)){
        $r[] = $row[0];
    };
    if (count($row) == 0)
        $r = 0;
    return $db_array = array(
        'count' => count($r),
        'row' => $r,
        'smi_id' => $smi_id
    );
}
?>