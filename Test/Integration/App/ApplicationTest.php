<?php


namespace IanLessa\ProductSearch\Test\Integration\App;

use IanLessa\ProductSearch\Aggregates\Pagination;
use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\Aggregates\Sort;
use IanLessa\ProductSearch\Repositories\MySQL\Product;
use IanLessa\ProductSearch\SearchService;
use IanLessa\ProductSearch\Test\Integration\AbstractBaseIntegrationTest;
use IanLessa\ProductSearchApp\Application;
use Slim\Http\Request;

class ApplicationTest extends AbstractBaseIntegrationTest
{
    /**
     * @var Application
     */
    private $appInstance;

    public function setUp()
    {
        parent::setUp();
        $this->appInstance = new Application($this->config);
    }

    /**
     * @test
     * @covers \IanLessa\ProductSearchApp\Application::createProductRepository
     *
     * @uses \IanLessa\ProductSearchApp\Application::__construct
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::__construct
     * @uses \IanLessa\ProductSearchApp\Application::setupRoutes
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::getConnectionClass
     *
     */
    public function createProductRepositoryShouldReturnMySQLProductRepository()
    {
        $repository = $this->appInstance->createProductRepository();

        $this->assertInstanceOf(Product::class, $repository);

    }

    /**
     * @test
     * @covers \IanLessa\ProductSearchApp\Application::createSearchFromGet
     *
     * @uses \IanLessa\ProductSearchApp\Application::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\Aggregates\Search::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setSort
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     * @uses \IanLessa\ProductSearchApp\Application::setupRoutes
     *
     */
    public function onCorrectParamsCreateSearchFromGetShouldReturnCorrectSearchObject()
    {
        $params = [
            'q' => 'term',
            'start_page' => '1',
            'per_page' => '20',
            'filter' => 'brand:brand_name',
            'sort' => 'asc:description'
        ];
        $correctSearch = new Search();
        $correctSearch->setPagination(new Pagination(1,20));
        $correctSearch->setFilters([
            'brand' => "brand_name",
            'name' => "term"
        ]);
        $correctSearch->setSort(Sort::asc('description'));

        $search = $this->appInstance->createSearchFromGet($params);

        $this->assertEquals($correctSearch, $search);
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearchApp\Application::__construct     *
     * @covers \IanLessa\ProductSearchApp\Application::getSlimApp
     * @covers \IanLessa\ProductSearchApp\Application::run
     * @covers \IanLessa\ProductSearchApp\Application::setupRoutes
     */
    public function invalidRouteShouldReturn404()
    {
        $env = \Slim\Http\Environment::mock([
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => '/invalid',
        ]);

        $req = Request::createFromEnvironment($env);
        $this->appInstance->getSlimApp()->getContainer()['request'] = $req;

        $response = $this->appInstance->run(true);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(Application::NOT_FOUND_MESSAGE, $response->getBody());
        $this->assertNull($this->appInstance->run(true));
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearchApp\Application::__construct
     * @covers \IanLessa\ProductSearchApp\Application::createProductRepository
     * @covers \IanLessa\ProductSearchApp\Application::createSearchFromGet
     * @covers \IanLessa\ProductSearchApp\Application::getSlimApp
     * @covers \IanLessa\ProductSearchApp\Application::run
     * @covers \IanLessa\ProductSearchApp\Application::setupRoutes
     *
     * @uses \IanLessa\ProductSearch\AbstractEntity::getId
     * @uses \IanLessa\ProductSearch\AbstractEntity::setId
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::getPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::getStart
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::jsonSerialize
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\Aggregates\Product::getBrand
     * @uses \IanLessa\ProductSearch\Aggregates\Product::getDescription
     * @uses \IanLessa\ProductSearch\Aggregates\Product::getName
     * @uses \IanLessa\ProductSearch\Aggregates\Product::jsonSerialize
     * @uses \IanLessa\ProductSearch\Aggregates\Product::setBrand
     * @uses \IanLessa\ProductSearch\Aggregates\Product::setDescription
     * @uses \IanLessa\ProductSearch\Aggregates\Product::setName
     * @uses \IanLessa\ProductSearch\Aggregates\Search::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Search::getFilters
     * @uses \IanLessa\ProductSearch\Aggregates\Search::getPagination
     * @uses \IanLessa\ProductSearch\Aggregates\Search::getSort
     * @uses \IanLessa\ProductSearch\Aggregates\Search::jsonSerialize
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setSort
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::getMaxRows
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::getResults
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::getRowCount
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::getSearch
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::jsonSerialize
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::setMaxRows
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::setResults
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::setSearch
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::desc
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::jsonSerialize
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     * @uses \IanLessa\ProductSearch\Repositories\AbstractRepository::__construct
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::fetch
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::formatResults
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::getBaseQuery
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::getConnectionClass
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::getMaxRows
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::getPaginationQuery
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::getSortQuery
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::getWhereQuery
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::preparePaginationQuery
     * @uses \IanLessa\ProductSearch\Repositories\MySQL\Product::prepareWhereQuery
     * @uses \IanLessa\ProductSearch\SearchService::__construct
     * @uses \IanLessa\ProductSearch\SearchService::searchProduct
     */
    public function variousQueriesShouldReturnTheCorrectResult()
    {
        foreach ($this->expectedData->tests as $expectedData) {
            if (!isset($expectedData->query)) {
                continue;
            }
            $this->setUp();
            $mock = [
                'REQUEST_METHOD' => 'GET',
                'REQUEST_URI' => '/products',

            ];
            if ($expectedData->query !== null) {
                $mock['QUERY_STRING'] = $expectedData->query;
            }

            $env = \Slim\Http\Environment::mock($mock);

            $req = Request::createFromEnvironment($env);
            $this->appInstance->getSlimApp()->getContainer()['request'] = $req;

            $response = $this->appInstance->run(true, true);
            $objectResponse = json_decode($response->getBody());

            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals($expectedData->result, $objectResponse);
        }
    }
}
