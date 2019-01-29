<?php

//  $rss_flag - 1 - from rss;
//              0 - another;

function mvestnik($news_url, $news_id, $rss_flag, $city_array) {

    $ch = curl_init($news_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Android; Tablet; rv:13.0) Gecko/13.0 Firefox/13.0');
    $html = curl_exec($ch);

    $document = phpQuery::newDocument($html);
    $news_div = $document->find('div#column_left');

    $header = $news_div->find('h1')->text();
    $pattern = '#«|»#';
    $header = preg_replace($pattern, '"', $header);
    $pattern = '#—#';
    $header = preg_replace($pattern, '"', $header);

    if ($rss_flag == 0) {
        $news_div = pq($news_div);
        $date = $news_div->find('span.link_icon.icon_date.rubric_icon_date');
    }
    $author = $news_div->find('p.article_author')->text();
    // может быть пустым

    $content = $news_div->find('div > p')->html();
    $content = strip_tags($content, '<br>');

    $city = array();
    preg_match('#^\s*([А-Я\s]*?)\.#', $content, $res);
    if (count($res[0]) != 0) {
        $city = $res[1];
    } else { $city = ''; }

    $content  = preg_replace('#^\s*[А-Я\s]*?\.#','',$content);

    $pattern = '#«|»#';
    $content = preg_replace($pattern, '"', $content);
    $pattern = '#—#';
    $content = preg_replace($pattern, '"', $content);
    $pattern = '#Читайте еще на сайте.+#iu';
    $content = preg_replace($pattern, '', $content);

/////////////////   tipical for all function ///////////////////////

    $city_id = array_search($city, $city_array);
    if ($city_id == FALSE) {
        $city_id = 47;                                  // неизвестный город
    }

    global $db;

    if ($rss_flag == 1 ) {
        $sql = "UPDATE news_tbl SET news_content = :param2, news_header = :param3, 
              city_id = :param4, news_parse = 1 WHERE news_id = :param5";
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

}
?>