<?php

function parse_debug ($news_url = 0, $news_id = 0)
{
//  функция получает URL и вызывает соответствующию функцию парсинга

//    $start = microtime(true);

    global $db;
    $city_array = array();
    $sql = "SELECT city_id, city_name FROM smi.city_tbl";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NAMED)) {
        $city_array[$row['city_id']] = $row['city_name'];
    }

    if ($news_id == 0) {
        $sql = "SELECT news_id, news_rss, smi_id FROM smi.news_tbl 
              WHERE news_url = :param1";
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':param1', $news_url, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_NAMED);

        $news_id = $row['news_id'];
        $news_rss = $row['news_rss'];
        $smi_id = $row['smi_id'];
    } else {
        $sql = "SELECT news_url, news_rss, smi_id FROM smi.news_tbl
              WHERE news_id = :param1";
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':param1', $news_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_NAMED);

        $news_url = $row['news_url'];
        $news_rss = $row['news_rss'];
        $smi_id = $row['smi_id'];
    }
        $sql = "SELECT smi_parsecontentfun FROM smi.smi_tbl 
              WHERE smi_tbl.smi_id = :param1";
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':param1', $smi_id, PDO::PARAM_INT);
        $stmt->execute();
        $smi = $stmt->fetch(PDO::FETCH_NAMED);
        $parse_fun = $smi['smi_parsecontentfun'];

    $result = call_user_func($parse_fun, $news_url, $news_id, $news_rss, $city_array);

//    echo 'Время выполнения скрипта: ' . (microtime(true) - $start) . ' сек.';
}

?>