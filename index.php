<?php

require 'vendor/autoload.php';

try {
    $configJson = file_get_contents('app/config/config.json');
    $config = json_decode($configJson, true);
}catch(Exception $e) {
    $config = [];
}

$app = new \IanLessa\ProductSearchApp\Application($config);
$app->run();