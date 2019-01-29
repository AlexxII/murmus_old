<html>
<head>
    <title>Обработка сообщений</title>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8"/>

    <script type="text/javascript" src="/js/jquery.js"></script>
    <script src="/js/datepicker.min.js"></script>
    <script src="/js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/js/news_js.js"></script>

    <link rel="stylesheet" type="text/css" href="/css/datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="/css/main_style.css">
    <link rel="stylesheet" type="text/css" href="/css/buttons.css">

    <style type="text/css">
        .back {
            display: none;
        }
    </style>


</head>


<?php
    require('scripts/db/db_connect.php');
    global $db;
// выгрузка массива городов для формирования select
    $city_array = array();
    $sql = "SELECT city_name, city_code FROM city_tbl WHERE city_id != 47";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $city_array = $stmt->fetchAll(PDO::FETCH_NAMED);
?>


<body>
        <div class="t_menu">
            <?php // echo '-'; ?>
        </div>

        <div class="top_bar">
            <?php echo ' '; ?>
        </div>

        <div class="main_wrap">
            <div class="side_bar">
            </div>

            <div class="right_side">
                <div class="left_news_wrap">
                    <div class="overlay" style="display: none"></div>

                    <?php
                    $ds = 123;

                    global $db;

                    $sql = "SELECT news_id, news_url, news_date, news_header, news_author, news_content, smi_name,
                            smi_code, city_name FROM news_tbl
                              RIGHT JOIN smi_tbl ON news_tbl.smi_id = smi_tbl.smi_id
                              RIGHT JOIN city_tbl ON news_tbl.city_id = city_tbl.city_id
                            WHERE news_tbl.news_parse = 1 ORDER BY news_date DESC;";

                    $stmt = $db->prepare($sql);
                    $stmt->execute();

                    foreach ($stmt as $row) {

                    $smi_date = substr($row['news_date'], 0, 10);
                    $smi_time = substr($row['news_date'], 11, 5);

                    echo '<div class="news_block">
                        <div class="news_info_block">
                            <div class="news_service_block">
                                ==/СМИ <br>
                                01/' . $row['smi_code'] . '<a href="'. $row['news_url'] .'">' . $row['smi_name']. ' ' . $smi_time . '</a><br>
                                02/' . $smi_date . '<br>
                                03/' . $row['city_name'] . '<br>
                                07/' . '<span style="color:black; font-weight: 600">' . $row['news_header'] . '</span> <br>
                                08/' . $row['news_author'] . '<br>
                                11/' . date('Y-m-d') . '<br>
                            </div>
                            <div class="news_city_block">
                                <div class="ltle_header">
                                    <span class="button24">Анализ дублей</span>
                                    <span class="back">Назад</span><br>
                                </div>';

                                $ar_size = count($city_array);
                                for ($j=0; $j<=15; $j++){
                                    echo '<span class="button15">' . $city_array[$j]['city_name'] . '</span>';
                                };
                                echo  '<select class="city_select" name="city_select">';
                                    for ($i=16; $i< $ar_size; $i++) {
                                        echo "<option value=" .$i. " ";
                                        echo ">" . $city_array[$i]['city_name'] . "</option>";
                                    };
                                       echo '</select>';
                     echo  '</div>
                        </div>
                        <div class="news_content">
                            06/ ' . $row['news_content'] . '
                        </div>
                    </div>';
                    }
                            ?>
                </div>

                <div class="right_news_wrap" style="display: inline-block">
                     <?php

                    $ds = 123;
                    for ($j=0; $j<2; $j++) {

                echo '<div class="news_block">
                        <div class="news_info_block">
                            <div class="news_service_block">
                                СМИ ' . $j . '<br>
                                01/' . $ds . '<br>
                                03/' . $ds . '<br>
                                07/' . $ds . '<br>
                            </div>
                            <div class="news_city_block">
                                456 5555555555555 55555555555555 55555555555555 55555555555<br>
                                456 55555555555555555555555555555555555555555555555555555<br>
                            </div>
                        </div>
                        <div class="news_content">
                            /06 ' . $ds . '
                        </div>
                    </div>';
                    }
                    ?>
                    <div class="trash"></div>
                </div>
            </div>
            <div class="footer">1</div>
        </div>


</body>
</html>

