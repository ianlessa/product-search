<?php

namespace IanLessa\ProductSearch\Test\Unit;

use IanLessa\ProductSearch\Aggregates\Product;
use PHPUnit\Framework\TestCase;

class AbstractEntityTest extends TestCase
{
    private $sampleId;


    protected function setUp()
    {
        $this->sampleId = 'test';
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\AbstractEntity::getId
     * @covers \IanLessa\ProductSearch\AbstractEntity::setId
     */
    public function anAbstractEntityGettersAndSettersShouldWorkAsExpected()
    {
        $product = new Product();
        $product->setId($this->sampleId);

        $this->assertEquals($this->sampleId, $product->getId());
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\AbstractEntity::equals
     *
     * @uses \IanLessa\ProductSearch\AbstractEntity::getId
     * @uses \IanLessa\ProductSearch\AbstractEntity::setId
     */
    public function abstractEntitiesShouldBeComparable()
    {
        $product1 = new Product();
        $product1->setId($this->sampleId);

        $product2 = new Product();
        $product2->setId($this->sampleId);

        $product3 = new Product();
        $product3->setId($this->sampleId . $this->sampleId);

        $this->assertTrue($product1->equals($product2));
        $this->assertFalse($product1->equals($product3));
    }
}
