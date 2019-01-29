<?php
    error_reporting(E_ALL & ~E_NOTICE);

    require_once ('function.php');

    content_parse();
//    content_parse(1,3);          // по необходимости можно изменять логику парсинга
                                            // выбрать СМИ и репозиторий

// составить хранимые процедуры для
// учет limit и функционал debug

function content_parse($smi_id1 = 0, $smi_id2 = 999, $limit = '10000', $debug = 0) {
    ini_set("max_execution_time", "300");
    global $db;

    $start = microtime(true);

    $city_array = array();
    $sql = "SELECT city_id, city_name FROM smi.city_tbl";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_NAMED))
    {
        $city_array[$row['city_id']] = $row['city_name'];
    }

    $sql = "SELECT smi_id, smi_parsecontentfun FROM smi.smi_tbl 
              WHERE smi_tbl.smi_id >= :param1 AND smi_tbl.smi_id <= :param2";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(':param1' => $smi_id1,
                        ':param2' => $smi_id2 ));

    foreach ($stmt as $smi ) {
        $smi_id = $smi['smi_id'];
        $parse_fun = $smi['smi_parsecontentfun'];

        $sql = "SELECT news_id, news_url, news_rss FROM smi.news_tbl 
                  WHERE news_tbl.smi_id = :param1 AND news_parse = 0 LIMIT :param2";  // AND news_id = 4516";
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':param2', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':param1', $smi_id, PDO::PARAM_INT);
        $stmt->execute();

        foreach ($stmt as $sm_i) {
            $news_id = $sm_i['news_id'];
            $news_url = $sm_i['news_url'];
            $news_rss = $sm_i['news_rss'];
            $result = call_user_func($parse_fun, $news_url, $news_id, $news_rss, $city_array);
        }
    }
    echo 'Время выполнения скрипта: '.(microtime(true) - $start).' сек.';
}

?>