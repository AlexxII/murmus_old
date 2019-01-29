<?php

function hibiny($news_url, $news_id, $rss_flag, $city_array) {

    // rss_flag - новость взята из rss или парсером.

    $ch = curl_init($news_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 5.1.1; Nexus 4 Build/LMY48T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.89 Mobile Safari/537.36');
    $html = curl_exec($ch);

    $html = iconv('windows-1251', 'utf-8', $html);
    $document = phpQuery::newDocument($html);

//////////////////////////////////////////////////////////////////////////////////////////////////////////
    $elements = $document->find('table table table > tr > td');
    $header = $elements->find('h3.p')->text();
    $pattern = '#«|»#';
    $header = preg_replace($pattern, '"', $header);
    $pattern = '#—#';
    $header = preg_replace($pattern, '"', $header);

    $date = $elements->find('p b')->html();

    $text = $elements;
    $table = pq($text)->find('table:eq(0)');
    $trs = pq($table)->find('tr');

    foreach ($trs as $tr) {
        $tr = pq($tr);

        if (($tr->find('img')->length() != 0)) {
            continue;
        }
        $tr->find('span')->remove();
        $tr->find('iframe')->remove();
        $content = $tr->html();

// del repeating HTML tags
        $content = preg_replace('/(<[^>]*>)(?:\s*\1)+/',"$1",$content);
        $content = strip_tags($content, '<br>');
        $content = preg_replace('/\s{2,}/',' ',$content);
    }
    $pattern = '#>\s*([а-я\s]+)\s*<#iu';
    if (preg_match_all($pattern, $date, $result) != 0) {
        $city = $result[1][0];
    } else {$city = 'Мурманская область'; }

    $pattern = '#«|»#';
    $content = preg_replace($pattern, '"', $content);

    $pattern = '#—#';
    $content = preg_replace($pattern, '-', $content);

    if ($rss_flag == 1) {

        $pattern = '#\s*([0-9]+)\s*([а-я]{3})\s*([0-9]{4}).*([0-9]{2}:[0-9]{2})[^<]#iu';

        if (preg_match_all($pattern, $date, $res) != 0) {

            $day = $res[1][0];
            $month = $res[2][0];
            $year = $res[3][0];
            $time = $res[4][0];

            if ($day == '0' || $time == '0') {
                parse_error($news_id);
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

/////////////////   tipical for all parse subfunctions ///////////////////////

    $city_id = array_search($city, $city_array);
    if ($city_id == FALSE) {
        $city_id = 47;                                  // неизвестный город
    }

    global $db;
    if ($rss_flag == 1 ) {
        $sql = "UPDATE news_tbl SET news_content = :param2, news_header = :param3, 
              city_id = :param4, news_parse = 1 WHERE news_id = :param6";
        $stmt = $db->prepare($sql);

        $stmt->execute(array(
            'param2' => $content,
            'param3' => $header,
            'param4' => $city_id,
            'param6' => $news_id
        ));
    } else {
        $sql = "UPDATE news_tbl SET news_header = :param1, news_content = :param2, news_date = :param3,
              city_id = :param4, news_parse = 1 WHERE news_id = :param5";
        $stmt = $db->prepare($sql);

        $stmt->execute(array(
            'param1' => $header,
            'param2' => $content,
            'param3' => $date,
            'param4' => $city_id,
            'param5' => $news_id
        ));
    }

    phpQuery::unloadDocuments();
    curl_close($ch);
    return 0;                           // success
}

?>