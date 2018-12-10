<?php

namespace IanLessa\ProductSearchApp;

use IanLessa\ProductSearch\Repositories\MySQL\Product as ProductRepository;
use IanLessa\ProductSearch\SearchService;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

final class Application
{
    static private $instance;
    private $slimApp;

    private function __construct()
    {
        $this->slimApp = new \Slim\App;
    }

    static public function run()
    {
        if (self::$instance === null) {
            self::$instance = new self;
            self::$instance->setupRoutes();
            self::$instance->slimApp->run();
        }
    }

    private function setupRoutes()
    {
        $this->slimApp->get('/products', function (Request $request, Response $response, array $args) {

            $repository = new ProductRepository(
                'localhost',
                3306,
                'root',
                'root',
                'product_search'
            );

            $searchService = new SearchService($repository);
            $results = $searchService->searchProduct($request->getQueryParams());

            $resp = json_encode($results, JSON_PRETTY_PRINT);

            $response->getBody()->write($resp);

            return $response;
        });
    }
}