<?php

use IanLessa\ProductSearch\Database\MySQL;
use IanLessa\ProductSearch\Repositories\Product as ProductRepository;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;
$app->get('/products', function (Request $request, Response $response, array $args) {
    $params = $request->getQueryParams();

    $prodRepo = new ProductRepository();

    $resp = new stdClass;
    $resp->results = $prodRepo->search();;
    $resp->params = $params;

    $resp = json_encode($resp, JSON_PRETTY_PRINT);

    $response->getBody()->write($resp);

    return $response;
});
$app->run();