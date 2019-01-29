<?php

function hibiny($news_url, $news_id, $rss_flag) {

    // rss_flag - новость взята из rss или парсером.

    $ch = curl_init($news_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);

    $html = iconv('windows-1251', 'utf-8', $html);
    $document = phpQuery::newDocument($html);

//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $elements = $document->find('table table table table > tr');
    $ter = pq($elements)->find('td');

    $header = $elements->find('h3.p')->html();
    $date = $elements->find('p b')->html();
    $content = $elements->find('table')->html();

    $p = '#<\s*span\s*class\s*[^>]*>[^<]*.*<.*/.*span[^>]*>#iu';
    $content = preg_replace($p, '',$content);

    echo $content;

    $pattern = '#<br>([^<>]*)<br>#';
    preg_match_all($pattern, $content, $result);
    var_dump($result);





    $pattern = '#>([а-я]+)<#iu';
    preg_match_all($pattern, $date, $result);
    $city = $result[1][0];

    if ($rss_flag == 1) {

        $pattern = '#\s*([0-9]+)\s*([а-я]{3})\s*([0-9]{4}).*([0-9]{2}:[0-9]{2})[^<]#iu';

        if (preg_match_all($pattern, $date, $res) != 0) {

            $day = $res[1][0];
            $month = $res[2][0];
            $year = $res[3][0];
            $time = $res[4][0];

            if ($day == '0' || $time == '0') {
                return;
            }

            switch ($month) {
                case 'Янв':
                    $month = '01';
                    break;
                case 'Фев':
                    $month = '02';
                    break;
                case 'Мар':
                    $month = '03';
                    break;
                case 'Апр':
                    $month = '04';
                    break;
                case 'Май':
                    $month = '05';
                    break;
                case 'Июн':
                    $month = '06';
                    break;
                case 'Июл':
                    $month = '07';
                    break;
                case 'Авг':
                    $month = '08';
                    break;
                case 'Сен':
                    $month = '09';
                    break;
                case 'Окт':
                    $month = '10';
                    break;
                case 'Ноя':
                    $month = '11';
                    break;
                case 'Дек':
                    $month = '12';
                    break;
                default:
                    $month = '0';
            }
            $date = $year . '-' . $month . '-' . $day . ", " . $time . ":00";
        }
    }
    echo '<br>';
    echo $date;
    echo '<br>';
    echo $city;

/////////////////   tipical for all parse subfunctions ///////////////////////
/*
    global $db;
    $sql = "SELECT city_id FROM smi.city_tbl WHERE city_tbl.city_name = :param1";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(':param1' => $city));
    foreach ($stmt as $city)
        $city_id = $city['0'];

    if ($city_id == '') {
        $city_id = 0;
    }


    $sql = "UPDATE news_tbl SET news_header = :param1, news_content = :param2, news_date = :param3,
              city_id = :param4, news_parse = 0 WHERE news_id = :param5";
    $stmt = $db->prepare($sql);

    $stmt->execute(array(
        'param1' => $header,
        'param2' => $content,
        'param3' => $date,
        'param4' => $city_id,
        'param5' => $news_id));            // flag - from RSS
*/

    phpQuery::unloadDocuments();
    curl_close($ch);
    return;                           // success
}

?>