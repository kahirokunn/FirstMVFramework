<?php

//スラッシュで区切られたurlを取得します
$analysis = explode('/', $_SERVER['PATH_INFO']);
$call;

foreach ($analysis as $value) {
    if ($value !== "") {
        $call = $value;
        break;
    }
}
