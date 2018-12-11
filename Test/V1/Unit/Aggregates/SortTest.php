<?php

namespace IanLessa\ProductSearch\Test\V1\Unit\Aggregates;

use \IanLessa\ProductSearch\V1\Aggregates\Sort;
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::asc
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::desc
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::getType
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::getValue
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::desc
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::jsonSerialize
     *
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Sort::isEqual
     *
     * @uses \IanLessa\ProductSearch\V1\AbstractValueObject::equals
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::__construct
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::asc
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::getValue
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setType
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Sort::setValue
     */
    public function aSortShouldBeComparable()
    {
        $sort1 = Sort::asc('test');
        $sort2 = Sort::asc('test');
        $sort3 = Sort::asc('notTest');

        $this->assertTrue($sort1->equals($sort2));
        $this->assertFalse($sort1->equals($sort3));
    }
}
