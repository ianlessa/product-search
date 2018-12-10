<?php

namespace IanLessa\ProductSearch;

use JsonSerializable;

class SearchResult implements JsonSerializable
{
    /**
     * @var Search
     */
    private $search;
    /**
     * @var array
     */
    private $results;

    /**
     * @var int 
     */
    private $rowCount;

    /**
     * SearchResult constructor.
     *
     * @param Search $search
     * @param array  $results
     */
    public function __construct(Search $search, array $results)
    {
        $this->setSearch($search);
        $this->setResults($results);
    }

    /**
     * @return Search
     */
    public function getSearch(): Search
    {
        return $this->search;
    }

    /**
     * @param  Search $search
     * @return SearchResult
     */
    public function setSearch(Search $search): SearchResult
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @param  array $results
     * @return SearchResult
     */
    public function setResults(array $results): SearchResult
    {
        $this->results = $results;
        return $this;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return count($this->results);
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
        $object = new \stdClass;

        $object->search = $this->getSearch();
        $object->rowCount = $this->getRowCount();
        $object->results = $this->getResults();

        return $object;
    }
}