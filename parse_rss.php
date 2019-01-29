<?php

require_once ('function.php');


// проверка наличия интернет соединения

//    $url = "https://www.hibiny.com/news/rss.php";
//    $url = "http://severpost.ru/rss.xml";
//    $url = "http://www.mvestnik.ru/data/rss/54/";
//    $url = "https://xn----7sbhwjb3brd.xn--p1ai/rss/arctic-tv.xml";


//    $url = "http://www.b-port.com/index.feed";
//    $url = "http://www.tv21.ru/rss/tv21.xml";
//    $url = "http://nord-news.ru/rss/nord-news.xml";
//    $url = "http://murman.tv/rss/gtrk.xml";
//    $url = "https://www.murman.ru/rss/rss.xml";
//    $url = "https://51.мвд.рф/news/rss";
//    $url = "http://prok-murmansk.ru/rss.xml";
//    $url = "https://gov-murman.ru/info/news/rss/";
//    $url = "http://murmansk.er.ru/core/siterss/";
//    $url = "http://51rus.org/yandex/news.xml";
//    $url = "http://murmansk.sledcom.ru/news/rss_verify/?main=0";
//    $url = "http://news.vmurmanske.ru/feed/";
//    $url = "http://www.duma-murman.ru/press-tsentr/news/rss/";
    //    $url = "http://region51.com/feed/articles/";                        //region51.com статьи
    //    $url = "http://region51.com/feed/interview/";                       //region51.com интервью
    //    $url = "http://region51.com/feed/events/";                          //region51.com события
//    $url = "http://region51.com/feed/news/";                            //region51.com новости
//    $url = "http://kprf-murman.ru/?feed=rss2";
//    $url = "http://bellona.ru/feed/";                                   //лента не только о МО
//    $url = "https://bloger51.com/feed";
//    $url = "http://levoradikal.ru/feed";


    function RSSParse($url, $smi_id)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $html = curl_exec($ch);
        $document = phpQuery::newDocument($html);

        $xml = simplexml_load_string($document);
        if ($xml === FALSE) {
            return 0;
        }
        else {
            $i = 0;
            foreach ($xml->channel->item as $item) {
                $news_header = $item->title;
                $date = date('Y-m-d H:i:s', strtotime($item->pubDate));
                $news_url = $item->link;

                global $db;
                $sql = "SELECT count(*) FROM news_tbl WHERE smi_id = :param1 and news_url = :param2";
                $stmt = $db->prepare($sql);
                $stmt->execute(array('param1' => $smi_id,
                    'param2' => $news_url));
                $row = $stmt->fetchColumn();

                if ($row == FALSE) {                     // if news_url is unic
                    $i++;
                    $sql = "INSERT INTO news_tbl(news_header, news_url, smi_id, news_date, news_rss) 
                                                VALUES (:param1, :param2, :param3, :param4, :param5)";
                    $stmt = $db->prepare($sql);

                    $stmt->execute(array('param1' => $news_header,
                        'param2' => $news_url,
                        'param3' => $smi_id,
                        'param4' => $date,
                        'param5' => 1));            // flag - from RSS
                } else {
                    continue;
                }
            }
        }
        return $i;
    }
?>