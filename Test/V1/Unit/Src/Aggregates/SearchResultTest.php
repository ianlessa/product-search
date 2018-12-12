<?php

namespace IanLessa\ProductSearch\Test\V1\Unit\Aggregates;

use \IanLessa\ProductSearch\V1\Aggregates\Search;
use \IanLessa\ProductSearch\V1\Aggregates\SearchResult;
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::jsonSerialize
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::getPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::getStart
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::jsonSerialize
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setStart
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
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setMaxRows
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setResults
     * @uses \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setSearch
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::__construct
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::getMaxRows
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::getResults
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::getRowCount
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::getSearch
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setMaxRows
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setResults
     * @covers \IanLessa\ProductSearch\V1\Aggregates\SearchResult::setSearch
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setSort
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
