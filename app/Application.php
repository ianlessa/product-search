<?php

namespace IanLessa\ProductSearchApp;

use IanLessa\ProductSearch\Pagination;
use IanLessa\ProductSearch\Repositories\MySQL\Product as ProductRepository;
use IanLessa\ProductSearch\Search;
use IanLessa\ProductSearch\SearchService;
use IanLessa\ProductSearch\Sort;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

final class Application
{
    /** @var Application */
    static private $instance;
    /**
     * @var \Slim\App
     */
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

            $repository = self::$instance->createProductRepository();
            $search = self::$instance->createSearchFromGet($request->getQueryParams());

            $searchService = new SearchService($repository);
            $results = $searchService->searchProduct($search);

            $resp = json_encode($results, JSON_PRETTY_PRINT);

            $response->getBody()->write($resp);

            return $response;
        });
    }

    private function createSearchFromGet($params) : Search
    {
        try {
            $filters = [];

            $term = $params["q"];

            if ($term !== null) {
                $filters['name'] = $term;
            }

            $filter = $params['filter'];
            if (preg_match('/.{1}:.{1}/', $filter) > 0) {
                $filter = explode(':', $filter);
                $filters[$filter[0]] = $filter[1];
            }

            $sort = null;
            $baseSort = $params['sort'];
            if (preg_match('/.{1}:.{1}/', $baseSort) > 0) {
                $baseSort = explode(':', $baseSort);
                $method = $baseSort[0];
                if (method_exists(Sort::class, $method)) {
                    $sort = Sort::$method($baseSort[1]);
                }
            }

            $pagination = new Pagination(
                $params["start_page"],
                $params["per_page"]
            );

            return new Search(
                $filters,
                $pagination,
                $sort
            );
        }catch(\Exception $e) {
            return new Search;
        }
    }

    private function createProductRepository()
    {
        return new ProductRepository(
            'localhost',
            3306,
            'root',
            'root',
            'product_search'
        );

    }
}