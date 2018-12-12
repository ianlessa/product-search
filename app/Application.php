<?php

namespace IanLessa\ProductSearchApp;

use IanLessa\ProductSearch\V1\Repositories\MySQL\Product as ProductRepositoryV1;
use IanLessa\ProductSearch\V1\SearchService  as SearchServiceV1;
use PDO;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

final class Application
{
    const NOT_FOUND_MESSAGE = 'Page not found';

    /**
     * @var bool 
     */
    static private $alreadyRan;
    /**
     * @var \Slim\App
     */
    private $slimApp;

    private $config;

    public function __construct(array $config = null)
    {

        $this->config = $config;

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

    public function run(bool $silent = false, bool $multipleRun = false) : ?\Slim\Http\Response
    {
        if (self::$alreadyRan === null || $multipleRun) {
            self::$alreadyRan = true;
            return $this->slimApp->run($silent);
        }
        return null;
    }

    private function setupRoutes()
    {
        $application = $this;
        $this->slimApp->get(
            '/v1/products', function (Request $request, Response $response, array $args) use ($application) {
                $repository = $application->createProductRepository();

                $searchService = new SearchServiceV1($repository);

                $search = $searchService->createSearchFromGet($request->getQueryParams());
                $results = $searchService->searchProduct($search);

                $resp = json_encode($results, JSON_PRETTY_PRINT);

                $response->getBody()->write($resp);

                return $response;
            }
        );
    }

    public function createProductRepository()
    {
        $host = $this->config['DB_HOST'] ?? 'localhost';
        $port = $this->config['DB_PORT'] ?? '3306';
        $database = $this->config['DB_DATABASE'] ?? 'product_search';
        $username = $this->config['DB_USERNAME'] ?? 'root';
        $password = $this->config['DB_PASSWORD'] ?? 'root';
        $dsn = "mysql:host=$host;port=$port;dbname=$database";

        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return new ProductRepositoryV1($pdo);
    }
}