<?php

function severpost($news_url, $news_id, $rss_flag, $city_array) {

    // rss_flag - новость взята из rss или парсером.

    $ch = curl_init($news_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $html = curl_exec($ch);
    $document = phpQuery::newDocument($html);

    $elements = $document->find('.c-post-block')->html();


    $text = pq($elements);
    $header = $text->find('h1')->text();
    $date = $text->find('.e-datetime')->text();
//    $text = $text->find('p:eq(0)');

    $pattern2 = '#<[^<]*iframe[^>]*>#';
    $pattern3 = '#<[^<]*(span|a)[^>]*>#';
    $text = preg_replace($pattern2, '', $text);
    $text = preg_replace($pattern3, '', $text);

    $pattern1 = '#<[^<]*hr[^>]*>[^_]*<\s*strong[^>]*>#';
    preg_match_all($pattern1, $text, $res);
    $text = $res[0][0];

    $pat = '#\s*(\d{2})\s*(\d{2})\s*(\d{4})\s*.*([0-9]{2}\s*:\s*[0-9]{2})#';
    if (preg_match_all($pat, $date, $res) != 0) {
        $date = $res[3][0] . "-" . $res[2][0] . "-" . $res[1][0] . ", " . $res[4][0] . ":00";
    } else { $date = "1970-01-01, 11:11:11"; }


//    echo $text;

/*


    $content = '';
    $pattern = '#\s*<p>([^(?:<p>)]*)<[^<>]*>#im';           // поиск контента между тегами
    if (preg_match_all($pattern, $text, $res) != 0) {
        $j = count($res[1]);
        for ($i=0; $i < $j; $i++) {
            $content .= $res[1][$i];
            $content .= '<br>';
        }
    } else { return -1; }

/////////////////   tipical for all function ///////////////////////

    global $db;
    $sql = "UPDATE news_tbl SET news_header = :param1, news_content = :param2, news_date = :param3,
              news_parse = 0 WHERE news_id = :param5";
    $stmt = $db->prepare($sql);

    $stmt->execute(array(
        'param1' => $header,
        'param2' => $content,
        'param3' => $date,
        'param5' => $news_id));
*/

    phpQuery::unloadDocuments();
    curl_close($ch);
    return 0;
}

?>