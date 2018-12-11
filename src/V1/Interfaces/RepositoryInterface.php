<?php

namespace IanLessa\ProductSearch\V1\Interfaces;

use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\SearchResult;

interface RepositoryInterface
{
    public function __construct($connection);
    public function fetch(Search $search) : SearchResult;
}