<?php

namespace IanLessa\ProductSearchApp;

use IanLessa\ProductSearch\Aggregates\Pagination;
use IanLessa\ProductSearch\Repositories\MySQL\Product as ProductRepository;
use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\SearchService;
use IanLessa\ProductSearch\Aggregates\Sort;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

final class Application
{
    /** @var Application */
    static private $instance;
    /** @var bool */
    static private $alreadyRan;
    /**
     * @var \Slim\App
     */
    private $slimApp;

    private function __construct()
    {
        $this->slimApp = new \Slim\App;
    }

    static public function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    static public function run()
    {
        if (self::$alreadyRan === null) {
            self::$alreadyRan = true;
            $instance = self::getInstance();
            $instance->setupRoutes();
            $instance->slimApp->run();
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

    public function createSearchFromGet($params) : Search
    {
        try {
            $filters = [];

            $term = $params["q"] ?? null;

            if ($term !== null) {
                $filters['name'] = $term;
            }

            $filter = $params['filter'];
            if (preg_match('/.{1}:.{1}/', $filter) > 0) {
                $filter = explode(':', $filter);
                $filters[$filter[0]] = $filter[1];
            }

            $sort = null;
            $baseSort = $params['sort'] ?? null;
            if (preg_match('/.{1}:.{1}/', $baseSort) > 0) {
                $baseSort = explode(':', $baseSort);
                $method = $baseSort[0];
                if (method_exists(Sort::class, $method)) {
                    $sort = Sort::$method($baseSort[1]);
                }
            }

            $pagination = new Pagination(
                $params["start_page"] ?? null,
                $params["per_page"] ?? null
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

    public function createProductRepository()
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