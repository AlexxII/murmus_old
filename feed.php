<?php

    require_once ('function.php');

    $smi_id = 0;

    feed(1,3);          // по необходимости можно изменять логику парсинга
                                     // выбрать СМИ и репозиторий

function feed($smi_id1 = 0, $smi_id2 = 999, $feed = 0) {
    ini_set("max_execution_time", "300");
    global $db;

    $sql = "SELECT smi_id, smi_name, smi_feed, smi_parsefun, smi_depth, smi_rss FROM smi.smi_tbl WHERE smi_tbl.smi_id >= :param1
            AND smi_tbl.smi_id <= :param2";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(':param1' => $smi_id1,
                          ':param2' => $smi_id2  ));

    foreach ($stmt as $smi) {
//        print_r($smi);
//        echo '<br>';
        $smi_id = $smi['smi_id'];
        $smi_name = $smi['smi_name'];
        $smi_feed = $smi['smi_feed'];
        $smi_parsefun = $smi['smi_parsefun'];
        $smi_depth = $smi['smi_depth'];
        $smi_rss = $smi['smi_rss'];

        if ($feed == 0) {
            $result = call_user_func('RSSParse',$smi_rss, $smi_id);
            if ( $result == 0) {
                call_user_func($smi_parsefun, $smi_feed, $smi_id, $smi_depth, $smi_name);
            }
        } else {
            call_user_func($smi_parsefun, $smi_feed, $smi_id, $smi_depth, $smi_name);
        }
    }
}


?>