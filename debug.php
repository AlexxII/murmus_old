<?php

require('scripts/db/db_connect.php');
require_once ('function.php');

echo '<link rel="stylesheet" type="text/css" href="/css/debug.css">';
echo '<script type="text/javascript" src="/js/jquery.js"></script>';
echo '<script type="text/javascript" src="/js/debug.js"></script>';



    echo '<div class="left_side">';
        echo '<form action="main_content_parse.php"> ';
        echo 'Ввод URLа: '.'<input style="margin-right: 20px" class="news_url" name="news_url"/>';
        echo 'Ввод ID: '.'<input style="margin-left: 5px" class="news_id" name="news_id"/>';
        echo '<input style="width: 100px; margin-left: 15px" value="Отправить" type="submit"><br>';
    echo '</div>';

    echo '<div class="right_side">';
        echo '<span class="load_badnews" style="cursor: pointer">Загрузить ошибки</span><br>';
    echo '</div>';




//    parse_debug(0,6504);



