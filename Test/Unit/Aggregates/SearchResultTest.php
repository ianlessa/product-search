<?php

namespace IanLessa\ProductSearch\Test\Unit\Aggregates;

use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\Aggregates\SearchResult;
use PHPUnit\Framework\TestCase;

class SearchResultTest extends TestCase
{

    private $sampleSearch;
    private $sampleRowCount;
    private $sampleMaxRows;
    private $sampleResults;

    protected function setUp()
    {
        $this->sampleSearch = new Search();
        $this->sampleResults = [1,2,3];
        $this->sampleRowCount = count($this->sampleResults);
        $this->sampleMaxRows = 10;
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::jsonSerialize
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::getPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::getStart
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::jsonSerialize
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setStart
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
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::setMaxRows
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::setResults
     * @uses \IanLessa\ProductSearch\Aggregates\SearchResult::setSearch
     */
    public function aSearchResultShouldBeCorrectlySerialized()
    {
        $object = new \stdClass;

        $object->search = $this->sampleSearch;
        $object->rowCount = $this->sampleRowCount;
        $object->maxRows = $this->sampleMaxRows;
        $object->results = $this->sampleResults;
        $serializedObject = json_encode($object);

        $searchResult = new SearchResult(
          $this->sampleSearch,
          $this->sampleMaxRows,
          $this->sampleResults
        );

        $serializedSearchResult = json_encode($searchResult);

        $this->assertEquals($serializedObject, $serializedSearchResult);
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::__construct
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::getMaxRows
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::getResults
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::getRowCount
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::getSearch
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::setMaxRows
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::setResults
     * @covers \IanLessa\ProductSearch\Aggregates\SearchResult::setSearch
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\Aggregates\Search::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setSort
     */
    public function searchResultGettersAndSettersShouldWorkAsExpected()
    {
        $searchResult = new SearchResult(
            $this->sampleSearch,
            $this->sampleMaxRows,
            $this->sampleResults
        );

        $this->assertEquals($this->sampleSearch, $searchResult->getSearch());
        $this->assertEquals($this->sampleMaxRows, $searchResult->getMaxRows());
        $this->assertEquals($this->sampleResults, $searchResult->getResults());
        $this->assertEquals($this->sampleRowCount, $searchResult->getRowCount());
    }
}
