<?php
    require('db_config.php');

try {
    $dsn = "mysql:host=$database_host;port=$port;dbname=$database_name;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE		        =>	PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE	=>  PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES	    =>	false,
    ];

    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo 'Error: ' .  $e->getMessage();
}

?>