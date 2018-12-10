<?php

use IanLessa\ProductSearch\Repositories\MySQL\Product as ProductRepository;
use IanLessa\ProductSearch\SearchService;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;
$app->get('/products', function (Request $request, Response $response, array $args) {

    $repository = new ProductRepository(
        'localhost',
        3306,
        'ian',
        'root'
    );

    $searchService = new SearchService($repository);
    $results = $searchService->searchProduct($request->getQueryParams());

    $resp = json_encode($results, JSON_PRETTY_PRINT);

    $response->getBody()->write($resp);

    return $response;
});
$app->run();