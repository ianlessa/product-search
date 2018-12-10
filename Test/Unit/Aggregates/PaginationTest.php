<?php

namespace IanLessa\ProductSearch\Test\Unit\Aggregates;

use IanLessa\ProductSearch\Aggregates\Pagination;
use IanLessa\ProductSearch\Exceptions\InvalidParamException;
use PHPUnit\Framework\TestCase;
use stdClass;

class PaginationTest extends TestCase
{
    /** @var int */
    private $sampleStart;
    /** @var int */
    private $samplePerPage;

    /** @var int */
    private $sampleIncorrectStart;
    /** @var int */
    private $sampleIncorrectPerPage;


    public function setUp()
    {
        $this->sampleStart = 10;
        $this->samplePerPage = 100;

        $this->sampleIncorrectStart = -10;
        $this->sampleIncorrectPerPage = 0;
    }

    /**
     * @test
     * @covers \IanLessa\ProductSearch\Aggregates\Pagination::default
     */
    public function defaultShouldReturnDefaultPagination()
    {
        $correctPagination = new Pagination(
        Pagination::DEFAULT_START,
        Pagination::DEFAULT_PERPAGE
        );

        $pagination = Pagination::default();

        $this->assertEquals($correctPagination->getStart(), $pagination->getStart());
        $this->assertEquals($correctPagination->getPerPage(), $pagination->getPerPage());
    }

    /**
     * @test
     * @covers \IanLessa\ProductSearch\Aggregates\Pagination::jsonSerialize
     */
    public function jsonSerializeShouldReturnSerializedObject()
    {
        $correctObject= new stdClass;
        $correctObject->start = $this->sampleStart;
        $correctObject->perPage = $this->samplePerPage;

        $correctSerializedObject = json_encode($correctObject);

        $pagination = new Pagination($this->sampleStart, $this->samplePerPage);
        $serializedObject = json_encode($pagination);

        $this->assertEquals($correctSerializedObject, $serializedObject);
    }

    /**
     * @test
     * @covers \IanLessa\ProductSearch\Aggregates\Pagination::getStart
     * @covers \IanLessa\ProductSearch\Aggregates\Pagination::getPerPage
     */
    public function gettersShouldReturnCorrectValues()
    {
        $pagination = new Pagination($this->sampleStart, $this->samplePerPage);

        $this->assertEquals($this->sampleStart, $pagination->getStart());
        $this->assertEquals($this->samplePerPage, $pagination->getPerPage());
    }

    /**
     * @test
     * @covers \IanLessa\ProductSearch\Aggregates\Pagination::setStart
     * @covers \IanLessa\ProductSearch\Aggregates\Pagination::setPerPage
     */
    public function aPaginationShouldNotBeCreatedWithLestThanOneParams()
    {
        $hit = 0;
        try {
            $pagination = new Pagination($this->sampleStart, $this->sampleIncorrectPerPage);
        }catch(InvalidParamException $e) {
            $hit++;
        }

        try {
            $pagination = new Pagination($this->sampleIncorrectStart, $this->samplePerPage);
        }catch(InvalidParamException $e) {
            $hit++;
        }

        try {
            $pagination = new Pagination($this->sampleIncorrectStart, $this->sampleIncorrectPerPage);
        }catch(InvalidParamException $e) {
            $hit++;
        }

        $this->assertEquals(3, $hit);
    }
}
