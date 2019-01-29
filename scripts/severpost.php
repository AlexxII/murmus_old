<?php

ini_set('log_errors', TRUE);            // Error logging
ini_set('error_log', 'D:\log.txt');

function severpost($news_url, $news_id, $rss_flag, $city_array) {

    $ers = 'error_severpost:';
//    $ert = date("Y-m-d H:i:s");

    // rss_flag - новость взята из rss или парсером.

    $ch = curl_init($news_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $html = curl_exec($ch);
    $document = phpQuery::newDocument($html);

    $elements = $document->find('.c-post-block')->html();

    $pattern5 = '#<h1[^>]*>([^\$]*)<\/*h1[^>]*>#iu';
    preg_match_all($pattern5, $elements, $res);
    if ( count($res[0]) != 0) {
        $header = $res[1][0];
    } else {
        $header = "";
    }

    $pattern = '#«|»#';
    $header= preg_replace($pattern, '"', $header);
    $pattern = '#—#';
    $header = preg_replace($pattern, '"', $header);

    if ($rss_flag != 1) {
        $pattern6 = '#\s*(\d{2})\s*(\d{2})\s*(\d{4})\s*.*([0-9]{2}\s*:\s*[0-9]{2})#';
        preg_match_all($pattern6, $elements, $res);
        if ( count($res[0]) != 0) {
            $date = $res[3][0] . "-" . $res[2][0] . "-" . $res[1][0] . ", " . $res[4][0] . ":00";
        } else {
            $date = "1970-01-01, 11:11:11";
        }
    }

    $pattern8 = '#<div[^>]*id=[^>]*>([^~]*?)<\/?div[^>]*>#';
    $pattern9 = '#(<br[^>]*>)[\s\n\r]*\1*#';
    $text = preg_replace($pattern8, '', $elements);
    $text = preg_replace($pattern9, '~', $text);    // замена <br> на ~

/*
    echo $date;
    echo '<br>';
    echo $header;
    echo '<br>';
    echo $text;
    echo '--------';
*/

    // символ $ - не должен появляться в тексте

    $pattern1 = '#((<img[^>]*>)|(<\/iframe[^>]*>))([^\$]*?)<div[^>]*>#';
    preg_match_all($pattern1, $text, $res);
    if (count($res) != 0) {
        $text = $res[0][0];
    } else {
        error_log($ers  . ' pattern1 break. URL - ' . $news_url);
        parse_error($news_id);
        return -1;
    }

    $pattern2 = '#<iframe[^>]*>[^~]*<\/iframe>#';
    $text = preg_replace($pattern2, '', $text);

//    echo $text;

    $pattern4 = '#<[^<]*hr[^>]*>([^\$]*)(?=Читайте также на С)#iu';
    preg_match_all($pattern4, $text, $res);
    if (count($res[0]) != 0) {
        $text = $res[0][0];
    } else {
        $pattern4 = '#<[^<]*hr[^>]*>([^\$]*)(?=<div\s*style=)#iu';
        preg_match_all($pattern4, $text, $res);
        if (count($res[0]) != 0) {
            $text = $res[0][0];
        } else {
            error_log($ers . ' pattern4 break. URL - ' . $news_url);
            parse_error($news_id);
            return -1;
        }
    }

    $pattern12 = '#</p[^>]*>#';
    $text = preg_replace($pattern12, '~', $text);
    $content = strip_tags($text);

    $pattern10 = '#(~)(?:\s*\1\s*)+#iu';
    $content = preg_replace($pattern10, '$1', $content);    //

    $pattern10 = '#~#iu';
    $content = preg_replace($pattern10, '<br>', $content);    //

    $pattern = '#«|»#';
    $content = preg_replace($pattern, '"', $content);

    $pattern = '#—#';
    $content = preg_replace($pattern, '"', $content);

    /////////////////   tipical for all function ///////////////////////

    $city_id = 47;                                              // неизвестный город

    global $db;
    if ($rss_flag == 1) {
        $sql = "UPDATE news_tbl SET news_content = :param2, news_header = :param3, city_id = :param4,
              news_parse = 1 WHERE news_id = :param5";
        $stmt = $db->prepare($sql);

        $stmt->execute(array(
            'param2' => $content,
            'param3' => $header,
            'param4' => $city_id,
            'param5' => $news_id
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
    return 0;
}

?>