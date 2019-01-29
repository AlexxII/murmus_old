<?php

require ('db/db_connect.php');
ini_set('display_errors',1);

$fp = fopen('d:/data.txt', 'a+');


$sql = "SELECT * FROM news_tbl";
$stmt = $db->prepare($sql);
$stmt->execute();

foreach ($stmt as $row)
{
    $news_content = $row['news_content'];
    $city_id = $row['city_id'];
    $smi_id = $row['smi_id'];
    $news_header = $row['news_header'];
    $news_author = $row['news_author'];
    $news_date = $row['news_date'];

    $sql = "SELECT smi_code FROM smi_tbl WHERE smi_id =" . $smi_id;
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM);
    $smi_code = $row[0];

    $sql = "SELECT city_code FROM city_tbl WHERE city_id =" . $city_id;
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_NUM);
    $city_code = $row[0];

    $SMI_1 = "СМИ" . "\r\n";
    $SMI_2 = "01/" . $news_date . "\r\n";
    $SMI_3 = "02/" . $smi_code . "\r\n";
    $SMI_4 = "03/" . $city_code . "\r\n";
    $SMI_5 = "04/" . $news_header . "\r\n";
    $SMI_6 = "06/" . $news_content . "\r\n";

//    fwrite($fp, $news_content);

    fwrite($fp, $SMI_1);
    fwrite($fp, $SMI_2);
    fwrite($fp, $SMI_3);
    fwrite($fp, $SMI_4);
    fwrite($fp, $SMI_5);
    fwrite($fp, $SMI_6 . "\n");

//    fwrite($fp, '\n');

}

?>