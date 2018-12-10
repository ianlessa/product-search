<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 10/12/18
 * Time: 20:30
 */

namespace IanLessa\ProductSearch\Test\Unit;

use IanLessa\ProductSearch\AbstractValueObject;
use IanLessa\ProductSearch\Aggregates\Pagination;
use IanLessa\ProductSearch\Aggregates\Sort;
use PHPUnit\Framework\TestCase;

class AbstractValueObjectTest extends TestCase
{

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\AbstractValueObject::equals
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::isEqual
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::default
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setPerPage
     * @uses \IanLessa\ProductSearch\Aggregates\Pagination::setStart
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
