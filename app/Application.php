<?php

namespace IanLessa\ProductSearchApp;

use IanLessa\ProductSearch\Aggregates\Pagination;
use IanLessa\ProductSearch\Repositories\MySQL\Product as ProductRepository;
use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\SearchService;
use IanLessa\ProductSearch\Aggregates\Sort;
use PDO;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

final class Application
{
    const NOT_FOUND_MESSAGE = 'Page not found';

    /** @var bool */
    static private $alreadyRan;
    /**
     * @var \Slim\App
     */
    private $slimApp;

    public function __construct($config)
    {
        $c['notFoundHandler'] = function ($c) {
            return function ($request, $response) use ($c) {
                return $response->withStatus(404)
                    ->withHeader('Content-Type', 'text/html')
                    ->write(self::NOT_FOUND_MESSAGE);
            };
        };

        $this->slimApp = new \Slim\App($c);
        $this->setupRoutes();
    }

    public function getSlimApp()
    {
        return $this->slimApp;
    }

    public function run()
    {
        if (self::$alreadyRan === null) {
            self::$alreadyRan = true;
            $this->slimApp->run();
        }
    }

    private function setupRoutes()
    {
        $application = $this;
        $this->slimApp->get('/products', function (Request $request, Response $response, array $args) use ($application) {

            $repository = $application->createProductRepository();
            $search = $application->createSearchFromGet($request->getQueryParams());

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

            $filter = $params['filter'] ?? null;
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
        $host = 'localhost';
        $port = '3306';
        $database = 'product_search';
        $username = 'root';
        $password = 'root';
        $dsn = "mysql:host=$host;port=$port;dbname=$database";

        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return new ProductRepository($pdo);

    }
}