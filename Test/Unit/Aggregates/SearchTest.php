<?php

namespace IanLessa\ProductSearch\Test\Unit\Aggregates;

use IanLessa\ProductSearch\Aggregates\Pagination;
use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\Aggregates\Sort;
use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{

    private $sampleFilters;
    private $samplePagination;
    private $sampleSort;

    protected function setUp()
    {
        $this->sampleFilters = ['brand' => 'brandName'];
        $this->samplePagination = Pagination::default();
        $this->sampleSort = Sort::asc('test');
    }


    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Search::__construct
     * @covers \IanLessa\ProductSearch\Aggregates\Search::getFilters
     * @covers \IanLessa\ProductSearch\Aggregates\Search::getPagination
     * @covers \IanLessa\ProductSearch\Aggregates\Search::getSort
     * @covers \IanLessa\ProductSearch\Aggregates\Search::setFilters
     * @covers \IanLessa\ProductSearch\Aggregates\Search::setPagination
     * @covers \IanLessa\ProductSearch\Aggregates\Search::setSort
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     */
    public function searchGettersAndSettersShouldWorkAsExpected()
    {
        $search = new Search(
            $this->sampleFilters,
            $this->samplePagination,
            $this->sampleSort
        );

        $this->assertEquals($this->sampleFilters, $search->getFilters());
        $this->assertEquals($this->samplePagination, $search->getPagination());
        $this->assertEquals($this->sampleSort, $search->getSort());
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Search::__construct
     * @covers \IanLessa\ProductSearch\Aggregates\Search::getFilters
     * @covers \IanLessa\ProductSearch\Aggregates\Search::getPagination
     * @covers \IanLessa\ProductSearch\Aggregates\Search::getSort
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setSort
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     *
     */
    public function anDefaultSearchShouldHaveTheCorrectParams()
    {
        $search = new Search();

        $this->assertEquals([], $search->getFilters());
        $this->assertEquals(Pagination::default(), $search->getPagination());
        $this->assertNull($search->getSort());
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Search::jsonSerialize
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
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\Aggregates\Search::setSort
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::jsonSerialize
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     */
    public function aSearchShouldBeCorrectSerialized()
    {
        $obj = new \stdClass;

        $obj->filters = $this->sampleFilters;
        $obj->pagination = $this->samplePagination;
        $obj->sort = $this->sampleSort;

        $serializedObj = json_encode($obj);

        $search = new Search(
            $this->sampleFilters,
            $this->samplePagination,
            $this->sampleSort
        );
        $serializedSearch = json_encode($search);

        $this->assertEquals($serializedObj, $serializedSearch);
    }

}