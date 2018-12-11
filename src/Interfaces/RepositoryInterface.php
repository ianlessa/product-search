<?php

namespace IanLessa\ProductSearch\Interfaces;

use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\Aggregates\SearchResult;

interface RepositoryInterface
{
    public function __construct($connection);
    public function fetch(Search $search) : SearchResult;
}