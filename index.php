<?php

use IanLessa\ProductSearch\Database\MySQL;
use IanLessa\ProductSearch\Pagination;
use IanLessa\ProductSearch\Repositories\Product as ProductRepository;
use IanLessa\ProductSearch\Search;
use IanLessa\ProductSearch\SearchResult;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;
$app->get(/**
 * @param Request $request
 * @param Response $response
 * @param array $args
 * @return Response
 */
    '/products', function (Request $request, Response $response, array $args) {
    $getQuery = $request->getQueryParams();

    $prodRepo = new ProductRepository();

    $params = new stdClass;
    $params->term = $getQuery["q"];
    $params->filter = explode(":", $getQuery["filter"]);
    $params->filter = [$params->filter[0] => $params->filter[1]];
    $params->sort = ['desc' => 'id'];
    $params->match = ['name', 'description'];

    $term = $getQuery["q"];
    $matches = [];
    $filters = [];

    $pagination = new Pagination(
        $getQuery["start_page"],
        $getQuery["per_page"]
    );

    $sort = null;

    $search = new Search(
       $term,
       $matches,
       $filters,
       $pagination,
       $sort
    );

    $results =  $prodRepo->fetch($search);

    $resp = json_encode($results, JSON_PRETTY_PRINT);

    $response->getBody()->write($resp);

    return $response;
});
$app->run();