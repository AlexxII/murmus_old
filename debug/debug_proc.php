<?php

$database_host = '127.0.0.1';
$port = '3306';		            		//port! !
$database_name = 'smi';
$username = 'root';  		        	// change
$password = '72PfgRjhGjrAbr';			// change
$charset = 'utf8';

$dsn = "mysql:host=$database_host;port=$port;dbname=$database_name;charset=$charset";
$db = new PDO($dsn, $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT news_id, news_url FROM news_tbl WHERE news_p_error = 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_NAMED);

    foreach ($rows as $row) {
        echo '<a href="/main_content_parse.php?id=' . $row['news_id'] . '">' . $row['news_url'] . '</a>';
        echo '<br>';
    }

?>