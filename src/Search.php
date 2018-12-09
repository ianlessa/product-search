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

    /** @var Sort */
    private $sort;

    /**
     * Search constructor.
     * @param string $term
     * @param Match[] $matches
     * @param Filter[] $filters
     * @param Pagination $pagination
     * @param Sort $sort
     */
    public function __construct(
        string $term = null,
        array $matches = null,
        array $filters = null,
        Pagination $pagination = null,
        Sort $sort = null
    ) {
        $this->setTerm($term);
        $this->setMatches($matches);
        $this->setFilters($filters);
        $this->setPagination($pagination);
        $this->setSort($sort);
    }

    /**
     * @return string
     */
    public function getTerm(): ?string
    {
        return $this->term;
    }

    /**
     * @param string $term
     * @return Search
     */
    public function setTerm(?string $term): Search
    {
        $this->term = $term;
        return $this;
    }

    /**
     * @return Match[]
     */
    public function getMatches(): ?array
    {
        return $this->matches ?? [];
    }

    /**
     * @param Match[] $matches
     * @return Search
     */
    public function setMatches(array $matches): Search
    {
        $this->matches = $matches;
        return $this;
    }

    /**
     * @return Filter[]
     */
    public function getFilters(): ?array
    {
        return $this->filters ?? [];
    }

    /**
     * @param Filter[] $filters
     * @return Search
     */
    public function setFilters(array $filters): Search
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
     * @param Pagination $pagination
     * @return Search
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
     * @param Sort $sort
     * @return Search
     */
    public function setSort(?Sort $sort): Search
    {
        $this->sort = $sort;
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

        $obj->term = $this->getTerm();
        $obj->matches = $this->getMatches();
        $obj->filters = $this->getFilters();
        $obj->pagination = $this->getPagination();
        $obj->sort = $this->getSort();

        return $obj;
    }
}