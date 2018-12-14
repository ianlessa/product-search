<?php

namespace IanLessa\ProductSearchApp;

use IanLessa\ProductSearch\V1\Repositories\MySQL\Product as ProductRepositoryV1;
use IanLessa\ProductSearch\V1\SearchService  as SearchServiceV1;
use IanLessa\ProductSearchApp\Services\Frontend;
use PDO;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Class Application
 *
 * The main responsability of this class is handle the requests by sending it to
 * the correct services, format it responses and then send it back to
 * frontend.
 *
 * @package IanLessa\ProductSearchApp
 */
final class Application
{
    /**
     * The message that will be printed on 404.
     */
    const NOT_FOUND_MESSAGE = 'Page not found';

    /**
     * Holds the state of the app run.
     *
     * @var bool 
     */
    static private $alreadyRan;
    /**
     * The Slim3 framework application class.
     *
     * @var \Slim\App
     */
    private $slimApp;

    /**
     * The configuration array, as setted on /app/config/config.json.
     *
     * @var array
     */
    private $config;

    /**
     * Application constructor.
     * Receives the config, set the default 404 response and call
     * the application routes configuration.
     *
     * @param array|null $config The config array
     */
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

    /**
     * Getter
     *
     * @return \Slim\App
     */
    public function getSlimApp()
    {
        return $this->slimApp;
    }

    /**
     * Run the Slim framework.
     *
     * @param  bool $silent      Should not send the output?
     * @param  bool $multipleRun Should ignore if the application was already run?
     * @return null|\Slim\Http\Response
     * @throws \Slim\Exception\MethodNotAllowedException
     * @throws \Slim\Exception\NotFoundException
     */
    public function run(bool $silent = false, bool $multipleRun = false) : ?\Slim\Http\Response
    {
        if (self::$alreadyRan === null || $multipleRun) {
            self::$alreadyRan = true;
            return $this->slimApp->run($silent);
        }
        return null;
    }

    /**
     * Init the application routes.
     *
     * @return null
     */
    private function setupRoutes()
    {
        $application = $this;

        /**
 * Index route. To show the Product Search Screen. 
*/
        $this->slimApp->get(
            '/', function (Request $request, Response $response, array $args) use ($application) {
                $frontendService = new Frontend();
                $frontPage = $frontendService->getFrontPage();

                $response->getBody()->write($frontPage);

                return $response;
            }
        );

        /**
 * API Route 
*/
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

    /**
     * Creates the concrete repository that will be used with the
     * ProductSearchService.
     *
     * @return ProductRepositoryV1
     * @throws \Exception
     */
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