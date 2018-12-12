<?php

namespace IanLessa\ProductSearch\Test\V1\Unit\Src;

use IanLessa\ProductSearch\Test\V1\Unit\ProductRepositoryMock;
use IanLessa\ProductSearch\V1\Aggregates\Pagination;
use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\Sort;
use IanLessa\ProductSearch\V1\SearchService;
use PHPUnit\Framework\TestCase;

class SearchServiceTest extends TestCase
{

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\V1\SearchService::searchProduct
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setSort
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
     * @uses \IanLessa\ProductSearch\V1\SearchService::__construct
     * @uses \IanLessa\ProductSearch\V1\SearchService::createSearchFromGet
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

        $searchService = new SearchService(new ProductRepositoryMock(null));

        $search = $searchService->createSearchFromGet($params);

        $this->assertEquals($correctSearch, $search);
    }
}
