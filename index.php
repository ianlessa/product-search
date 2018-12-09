<?php

use IanLessa\ProductSearch\Pagination;
use IanLessa\ProductSearch\Repositories\Product as ProductRepository;
use IanLessa\ProductSearch\Search;
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
    $filter = $getQuery["filter"] ;
    $filter = explode(":", $filter);
    $filter = [$filter[0] => $filter[1]];
    $params->sort = ['desc' => 'id'];

    $term = $getQuery["q"];
    $matches = ["name"];
    $filters = $filter;


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