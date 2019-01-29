<?php
//        require ('db/db_connect.php');

function InsertIntoDB ($news_str) {

    ini_set('display_errors',1);

    global $db;

    $news_url = $news_str['news_url'];
    $news_date = $news_str['news_date'];
    $news_date = str_replace(",", "", $news_date );
    $news_header = $news_str['news_header'];
    $news_region = $news_str['news_region'];
    $news_author = $news_str['news_author'];
    $news_content = $news_str['news_content'];
    $city_id = $news_str['city_id'];
    $smi_id = $news_str['smi_id'];

/*
    p_create - дата парсинга
    news_rss - 1 - URL собраны по RSS, 0 - при парсинге сайта
    news_parse - 1 - контент был спарсен; 0 - поке не спарсен
    news_selected - 1 - контент был выбран
    news_success - 1 - удача при парсинге; 0 - неудача
    news_unload_date - дата выгрузки ?????????

    echo $news_url;
    echo '<br>';
    echo $news_date;
    echo '<br>';
    echo $news_header;
    echo '<br>';
    echo $news_region;
    echo '<br>';
    echo $news_author;
    echo '<br>';
    echo $news_content;
    echo '<br>';
    echo $city_id;
    echo '<br>';
    echo $smi_id;
*/
//    try {

    $sql = "INSERT INTO news_tbl (news_date, news_url, news_content, city_id, smi_id, news_header,
                      news_region, news_author ) 
                          VALUES (:news_date, :news_url, :news_content, :city_id, :smi_id, :news_header,
                      :news_region, :news_author )";


    $query = $db->prepare($sql);
    $query->execute(array('news_date' => $news_date,
        'news_url' => $news_str['news_url'],
        'news_content' => $news_str['news_content'],
        'city_id' => $news_str['city_id'],
        'smi_id' => $news_str['smi_id'],
        'news_header' => $news_str['news_header'],
        'news_region' => $news_str['news_region'],
        'news_author' => $news_str['news_author']
        ));
//    }
//    catch(PDOException $e)
//    {
//        die("Error: " .$e->getMessage());
//    }

}
?>