<?php

//20 links on page

function parse_severpost ($url, $smi_id, $depth, $smi_name)
{
    ini_set("max_execution_time", "240");

    for ($i = 1; $i <= $depth; $i++) {

        $web_url = $url . $i;

        $ch = curl_init($web_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Android; Tablet; rv:13.0) Gecko/13.0 Firefox/13.0');
        $html = curl_exec($ch);
        $document = phpQuery::newDocument($html);

        $elements  = $document->find('div');
        $links = pq($elements)->find('h2 > a');

////////////////////// Typical for other parse functions
// put all unic news links into news_tbl


        foreach ($links as $link) {
            $nlink = pq($link)->attr('href');

            $news_url = "http://" . $smi_name . $nlink;

            global $db;
            $sql = "SELECT count(*) FROM news_tbl WHERE smi_id = :param1 and news_url = :param2";
            $stmt = $db->prepare($sql);
            $stmt->execute(array('param1' => $smi_id,
                'param2' => $news_url));
            $row = $stmt->fetchColumn();

            if ($row == FALSE) {                                 // if news_url if unic
                $sql = "INSERT INTO news_tbl(news_url, smi_id, news_date) VALUES (:param1, :param2, :param3)";
                $stmt = $db->prepare($sql);

                $date = date('Y-m-d H:i:s');              // date of the new

                $stmt->execute(array('param1' => $news_url,
                    'param2' => $smi_id,
                    'param3' => $date));
            } else {
                continue;
            }
        }
    }

    phpQuery::unloadDocuments();
    curl_close($ch);
}

?>