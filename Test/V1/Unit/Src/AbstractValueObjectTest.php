<?php

namespace IanLessa\ProductSearch\Test\V1\Unit\Src;

use \IanLessa\ProductSearch\V1\AbstractValueObject;
use \IanLessa\ProductSearch\V1\Aggregates\Pagination;
use \IanLessa\ProductSearch\V1\Aggregates\Sort;
use PHPUnit\Framework\TestCase;

class AbstractValueObjectTest extends TestCase
{

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\V1\AbstractValueObject::equals
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::isEqual
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Pagination::setStart
     */
    public function valueObjectsShouldBeComparable()
    {
        $sort1 = Sort::asc('test');
        $sort2 = Sort::asc('test');
        $sort3 = Sort::asc('nottest');

        $pagination = Pagination::default();

        $this->assertTrue($sort1->equals($sort2));
        $this->assertFalse($sort1->equals($sort3));
        $this->assertFalse($sort1->equals($pagination));
    }
}
