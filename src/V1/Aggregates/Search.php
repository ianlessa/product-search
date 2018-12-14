<?php

namespace IanLessa\ProductSearch\V1\Aggregates;

/**
 * Search Aggregate.
 * Holds the required information to do an search and the invariants related to
 * them.
 *
 * @package IanLessa\ProductSearch\V1\Aggregates
 */
final class Search implements \JsonSerializable
{
    /**
     * Holds an array of filters where the keys are the entity params that will be
     * filtered and the values are the terms to filter by. Example:
     * ["name" => "product_name"]
     * ["brand" => "product_brand"]
     *
     * @var array
     */
    private $filters;

    /**
     * @var Pagination The pagination object that will be used on the search.
     */
    private $pagination;

    /**
     * @var Sort  The sort object that will be used on the search.
     */
    private $sort;

    /**
     * Search constructor.
     *
     * @param array      $filters
     * @param Pagination $pagination
     * @param Sort       $sort
     */
    public function __construct(
        array $filters = null,
        Pagination $pagination = null,
        Sort $sort = null
    ) {
        $this->setFilters($filters);
        $this->setPagination($pagination);
        $this->setSort($sort);
    }

    /**
     * @return array If there are no filters defined, it will return an empty array.
     */
    public function getFilters(): array
    {
        return $this->filters ?? [];
    }

    /**
     * @param  array $filters
     * @return Search
     */
    public function setFilters(?array $filters): Search
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * @return Pagination
     */
    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }

    /**
     * The pagination setter. If the value passed is null, the default pagination
     * will be attributed to the parameter.
     *
     * @param  null|Pagination $pagination
     * @return Search
     * @throws \IanLessa\ProductSearch\V1\Exceptions\InvalidParamException
     */
    public function setPagination(?Pagination $pagination): Search
    {
        $this->pagination = $pagination ?? Pagination::default();
        return $this;
    }

    /**
     * @return Sort
     */
    public function getSort(): ?Sort
    {
        return $this->sort;
    }

    /**
     * @param  Sort $sort
     * @return Search
     */
    public function setSort(?Sort $sort): Search
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link   http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since  5.4.0
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass;

        $obj->filters = $this->getFilters();
        $obj->pagination = $this->getPagination();
        $obj->sort = $this->getSort();

        return $obj;
    }
}