<?php

//20 links on page

function parse_hibiny ($url, $smi_id, $depth, $smi_name)
{
    ini_set("max_execution_time", "240");

    $i = $depth*20;
    $web_url = $url . $i;

    $ch = curl_init($web_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Android; Tablet; rv:13.0) Gecko/13.0 Firefox/13.0');
    $html = curl_exec($ch);

    $html = iconv('windows-1251', 'utf-8', $html);

    $document = phpQuery::newDocument($html);

    $elements = $document->find('table table table table');
    $links = pq($elements)->find('td > a');


    foreach ($links as $link) {
        $nlink = pq($link)->attr('href');

        if (substr($nlink, 0, 14) == '/news/archive/') {
            $news_url = "https://" . $smi_name . $nlink;

            global $db;
            $sql = "SELECT count(*) FROM news_tbl WHERE smi_id = :param1 and news_url = :param2";
            $stmt = $db->prepare($sql);
            $stmt->execute(array('param1' => $smi_id,
                'param2' => $news_url));
            $row = $stmt->fetchColumn();

            if ($row == FALSE) {                     // if news_url is unic
                $sql = "INSERT INTO news_tbl(news_url, smi_id, news_date) VALUES (:param1, :param2, :param3)";
                $stmt = $db->prepare($sql);

                $date = date('Y-m-d H:i:s');              // date of the new

                $stmt->execute(array('param1' => $news_url,
                    'param2' => $smi_id,
                    'param3' => $date));
            } else {
                continue;
            }
        };
    }

    phpQuery::unloadDocuments();
    curl_close($ch);
}
