<?php

namespace IanLessa\ProductSearch\Test\V1\Integration\App;

use IanLessa\ProductSearch\Test\V1\Integration\AbstractBaseIntegrationTest;
use IanLessa\ProductSearch\V1\Repositories\MySQL\Product;
use IanLessa\ProductSearch\V1\SearchService;
use IanLessa\ProductSearchApp\Application;
use PDO;

class SearchServiceTest extends AbstractBaseIntegrationTest
{
    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\V1\SearchService::searchProduct
     * @covers \IanLessa\ProductSearch\V1\SearchService::createSearchFromGet
     * @covers \IanLessa\ProductSearch\V1\SearchService::__construct
     * @covers \IanLessa\ProductSearch\V1\Repositories\AbstractRepository::__construct
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::fetch
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::formatResults
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::getBaseQuery
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::getConnectionClass
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::getMaxRows
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::getPaginationQuery
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::getSortQuery
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::getWhereQuery
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::preparePaginationQuery
     * @covers \IanLessa\ProductSearch\V1\Repositories\MySQL\Product::prepareWhereQuery
     *
     * @uses \IanLessa\ProductSearch\V1\AbstractEntity::getId
     * @uses \IanLessa\ProductSearch\V1\AbstractEntity::setId
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::getPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::getStart
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::jsonSerialize
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::getBrand
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::getDescription
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::getName
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::jsonSerialize
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::setBrand
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::setDescription
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::setName
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::getFilters
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::getPagination
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::getSort
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::jsonSerialize
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setSort
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::getMaxRows
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::getResults
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::getRowCount
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::getSearch
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::jsonSerialize
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setMaxRows
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setResults
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setSearch
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::desc
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::jsonSerialize
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
     */
    public function variousParamsShouldReturnTheCorrectSearchResult()
    {
        foreach ($this->expectedData->tests as $expectedData) {
            if (!isset($expectedData->query)) {
                continue;
            }
            $params = $this->queryStringToParams($expectedData->query);

            $repository = new Product($this->pdo);
            $searchService = new SearchService($repository);
            $search = $searchService->createSearchFromGet($params);
            $searchResult = $searchService->searchProduct($search);

            $searchResult = json_encode($searchResult);
            $searchResult = json_decode($searchResult);

            $this->assertEquals($expectedData->result, $searchResult);
        }
    }

    private function queryStringToParams(?string $queryString) : array
    {
        if ($queryString === "") {
            return [];
        }
        $params = [];
        foreach (explode("&", $queryString) as $param ) {
            $data = explode('=', $param);
            $params[$data[0]] = $data[1];
        }
        return $params;
    }
}
