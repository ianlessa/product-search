<?php

namespace IanLessa\ProductSearch\Test\Unit\Aggregates;

use IanLessa\ProductSearch\Aggregates\Sort;
use PHPUnit\Framework\TestCase;

class SortTest extends TestCase
{
    private $sampleValue;
    private $sampleAscType;

    protected function setUp()
    {
        $this->sampleValue = 'test';
        $this->sampleAscType = Sort::TYPE_ASC;
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::asc
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     */
    public function sortAscShouldRetornAnAscSort()
    {
        $sort = Sort::asc('test');

        $this->assertEquals(Sort::TYPE_ASC, $sort->getType());
        $this->assertEquals('test', $sort->getValue());
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::desc
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     */
    public function sortDescShouldRetornAnDescSort()
    {
        $sort = Sort::desc('test');

        $this->assertEquals(Sort::TYPE_DESC, $sort->getType());
        $this->assertEquals('test', $sort->getValue());
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::getType
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::getValue
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::setValue
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::desc
     */
    public function sortGettersAndSettersShouldWorkAsExpected()
    {
        $sort = Sort::desc('test');

        $this->assertEquals(Sort::TYPE_DESC, $sort->getType());
        $this->assertEquals('test', $sort->getValue());
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::jsonSerialize
     *
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     */
    public function aSortShouldBeCorrectlySerialized()
    {
        $obj = new \stdClass();
        $obj->value = $this->sampleValue;
        $obj->type = $this->sampleAscType;
        $serializedObj = json_encode($obj);

        $sort = Sort::asc($this->sampleValue);
        $serializedSort = json_encode($sort);

        $this->assertEquals($serializedObj, $serializedSort);
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Sort::isEqual
     *
     * @uses \IanLessa\ProductSearch\AbstractValueObject::equals
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\Aggregates\Sort::setValue
     */
    public function aSortShouldBeComparable()
    {
        $sort1 = Sort::asc('test');
        $sort2 = Sort::asc('test');

        $this->assertTrue($sort1->equals($sort2));
    }
}