<?php

namespace IanLessa\ProductSearch\Test\V1\Unit;

use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\SearchResult;
use IanLessa\ProductSearch\V1\Interfaces\RepositoryInterface;

class ProductRepositoryMock implements RepositoryInterface
{

    public function __construct($connection)
    {
    }

    public function fetch(Search $search): SearchResult
    {
        // TODO: Implement fetch() method.
    }
}