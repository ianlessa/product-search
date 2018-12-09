<?php

namespace IanLessa\ProductSearch\Aggregates;

use IanLessa\ProductSearch\AbstractEntity;

class Brand extends AbstractEntity
{
    /** @var string */
    private $name;

    /**
     * Brand constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Brand
     */
    public function setName(string $name): Brand
    {
        $this->name = $name;
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
        $obj = new \stdClass;

        $obj->id = $this->id;
        $obj->name = $this->name;

        return $obj;
    }
}