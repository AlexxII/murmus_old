<?php

function severpost($news_url, $news_id, $rss_flag, $city_array) {

    // rss_flag - новость взята из rss или парсером.

    $ch = curl_init($news_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Android; Tablet; rv:13.0) Gecko/13.0 Firefox/13.0');

    $html = curl_exec($ch);

    $document = phpQuery::newDocument($html);

    $elements = $document->find('#text_cont')->html();
    $text = $elements;
    $text = pq($text);
    $header = $text->find('h1')->text();
    $date = $text->find('.sp-datetime')->text();
    $text->find('.c-comments-block')->remove();
    $text->find('div > h4')->remove();
    $text->find('div > .sp-datetime')->remove();
    $text->find('div > h1')->remove();
    $text->find('script')->remove();

    $content = '';
    $p_elements = $text->find('p');

    $count = $text->find('p')->count();
    foreach ($p_elements as $p) {
        $p = pq($p);
        $count--;
        if ($p->find('img')->length() != 0) {
            continue;
        }
        $p->find('iframe')->remove();
        if (substr($p->find('strong')->text(), 0 , 14) == "Читайте") {
            $p->find('strong')->remove();
//            $p->find('span')->remove();
        }
        $content .= $p->html();
        $content .= "<br>";

        $content = preg_replace('/(<[^>]*>)(?:\s*\1)+/','$1',$content);
        $content = strip_tags($content, '<br>');
        $content = preg_replace('/\s{2,}/',' ',$content);
    }
/////////////////   tipical for all function ///////////////////////

    global $db;
    $sql = "UPDATE news_tbl SET news_header = :param1, news_content = :param2, news_date = :param3,
              city_id = :param4, news_parse = 1 WHERE news_id = :param5";
    $stmt = $db->prepare($sql);

    $stmt->execute(array(
        'param1' => $header,
        'param2' => $content,
        'param3' => $date,
        'param5' => $news_id));


    phpQuery::unloadDocuments();
    curl_close($ch);
}

?>