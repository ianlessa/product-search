<?php

namespace IanLessa\ProductSearch\Test\Unit\Aggregates;

use IanLessa\ProductSearch\Aggregates\Product;
use PHPUnit\Framework\TestCase;
use stdClass;


class ProductTest extends TestCase
{

    private $sampleId;
    private $sampleName;
    private $sampleBrand;
    private $sampleDescription;

    protected function setUp()
    {
        $this->sampleId = 'Id';
        $this->sampleName = 'Name';
        $this->sampleBrand = 'Brand';
        $this->sampleDescription = 'Description';
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Product::setName
     * @covers \IanLessa\ProductSearch\Aggregates\Product::getBrand
     * @covers \IanLessa\ProductSearch\Aggregates\Product::getDescription
     * @covers \IanLessa\ProductSearch\Aggregates\Product::getName
     * @covers \IanLessa\ProductSearch\Aggregates\Product::setBrand
     * @covers \IanLessa\ProductSearch\Aggregates\Product::setDescription
     */
    public function gettersAndSettersShouldFunctionAsExpected()
    {
        $product = new Product();
        $product->setName($this->sampleName);
        $product->setBrand($this->sampleBrand);
        $product->setDescription($this->sampleDescription);

        $this->assertEquals($this->sampleName, $product->getName());
        $this->assertEquals($this->sampleBrand, $product->getBrand());
        $this->assertEquals($this->sampleDescription, $product->getDescription());
    }

    /**
     * @test
     *
     * @covers \IanLessa\ProductSearch\Aggregates\Product::jsonSerialize
     *
     * @uses \IanLessa\ProductSearch\AbstractEntity::getId
     * @uses \IanLessa\ProductSearch\AbstractEntity::setId
     * @uses \IanLessa\ProductSearch\Aggregates\Product::getBrand
     * @uses \IanLessa\ProductSearch\Aggregates\Product::getDescription
     * @uses \IanLessa\ProductSearch\Aggregates\Product::getName
     * @uses \IanLessa\ProductSearch\Aggregates\Product::setBrand
     * @uses \IanLessa\ProductSearch\Aggregates\Product::setDescription
     * @uses \IanLessa\ProductSearch\Aggregates\Product::setName
     */
    public function aProductShouldBeSerializedCorrectly()
    {
        $sample = new stdClass();
        $sample->id = $this->sampleId;
        $sample->name = $this->sampleName;
        $sample->brand = $this->sampleBrand;
        $sample->description = $this->sampleDescription;
        $serializedSample = json_encode($sample);

        $product = new Product();
        $product->setId($this->sampleId);
        $product->setName($this->sampleName);
        $product->setBrand($this->sampleBrand);
        $product->setDescription($this->sampleDescription);

        $serializedProduct = json_encode($product);

        $this->assertEquals($serializedSample, $serializedProduct);
    }

}