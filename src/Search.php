<?php

namespace IanLessa\ProductSearch;

final class Search implements \JsonSerializable
{
    /**
     * @var string
     */
    private $term;
    /**
     * @var Match[]
     */
    private $matches;
    /**
     * @var Filter[]
     */
    private $filters;
    /**
     * @var Pagination
     */
    private $pagination;

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}