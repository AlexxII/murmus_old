<?php

require_once ('C:/PHP/lib/phpmorphy/src/common.php');

$dir = 'C:/PHP/lib/phpmorphy/dicts';

$lang = 'ru_RU';

$opts = array(
        'storage' => PHPMORPHY_STORAGE_FILE
);



try {
    $morphy = new phpMorphy ($dir, $lang, $opts);
} catch (phpMorphy_Exception $e) {
    die('Error: ' . $e->getMessage());
}


$word = 'Холодные';

$base = $morphy->getBaseForm($word);

echo $base;

?>