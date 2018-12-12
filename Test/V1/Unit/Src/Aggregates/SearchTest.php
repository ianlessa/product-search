<?php

namespace IanLessa\ProductSearch\Test\V1\Unit\Aggregates;

use \IanLessa\ProductSearch\V1\Aggregates\Pagination;
use \IanLessa\ProductSearch\V1\Aggregates\Search;
use \IanLessa\ProductSearch\V1\Aggregates\Sort;
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::__construct
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::getFilters
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::getPagination
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::getSort
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::setFilters
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::setPagination
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::setSort
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::__construct
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::getFilters
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::getPagination
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::getSort
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setStart
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setSort
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Search::jsonSerialize
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
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setFilters
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setPagination
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Search::setSort
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::jsonSerialize
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
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