<?php


namespace IanLessa\ProductSearch\Test\Integration\App;

use IanLessa\ProductSearch\Aggregates\Pagination;
use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\Aggregates\Sort;
use IanLessa\ProductSearch\Repositories\MySQL\Product;
use IanLessa\ProductSearchApp\Application;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * @var Application
     */
    private $appInstance;

    public function setUp()
    {
        $this->appInstance = Application::getInstance();
    }

    /**
     * @test
     * @covers \IanLessa\ProductSearchApp\Application::createProductRepository
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
     * @covers \IanLessa\ProductSearchApp\Application::createSearchFromGet
     *
     */
    public function onIncorrectParamsCreateSearchFromGetShouldReturnDefaultSearchObject()
    {
        $params = [
            'start_page' => '-1',
            'per_page' => 'as200',
            'filter' => 'branqd:brand_name',
            'sort' => 'asffc:description'
        ];
        $defaultSearch = new Search();

        $search = $this->appInstance->createSearchFromGet($params);

        $this->assertEquals($defaultSearch, $search);
    }
}
