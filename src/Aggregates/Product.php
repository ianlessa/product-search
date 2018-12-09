<?php

namespace IanLessa\ProductSearch\Aggregates;

use IanLessa\ProductSearch\AbstractEntity;

class Product extends AbstractEntity
{
    /** @var string */
    private $name;
    /** @var Brand */
    private $brand;
    /** @var string */
    private $description;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Brand
     */
    public function getBrand(): Brand
    {
        return $this->brand;
    }

    /**
     * @param Brand $brand
     * @return Product
     */
    public function setBrand(Brand $brand): Product
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Product
     */
    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $data = new \stdClass;

        $data->id = $this->getId();
        $data->name = $this->getName();
        $data->brand = $this->getBrand();
        $data->description = $this->getDescription();

        return $data;
    }
}