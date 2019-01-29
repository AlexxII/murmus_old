<?php

function parse_error ($news_id)
{
    global $db;
    $sql = "UPDATE news_tbl SET news_p_error = 1 WHERE news_tbl.news_id = :param1";
    $stmt = $db->prepare($sql);
    $stmt->execute(array(
        'param1' => $news_id
    ));
}

?>