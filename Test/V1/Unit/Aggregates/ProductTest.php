<?php

namespace IanLessa\ProductSearch\Test\V1\Unit\Aggregates;

use \IanLessa\ProductSearch\V1\Aggregates\Product;
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Product::setName
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Product::getBrand
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Product::getDescription
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Product::getName
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Product::setBrand
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Product::setDescription
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
     * @covers \IanLessa\ProductSearch\V1\Aggregates\Product::jsonSerialize
     *
     * @uses \IanLessa\ProductSearch\V1\AbstractEntity::getId
     * @uses \IanLessa\ProductSearch\V1\AbstractEntity::setId
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::getBrand
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::getDescription
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::getName
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::setBrand
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::setDescription
     * @uses \IanLessa\ProductSearch\V1\Aggregates\Product::setName
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