<?php

namespace IanLessa\ProductSearch\Interfaces;

use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\Aggregates\SearchResult;

interface RepositoryInterface
{
    public function __construct(
        string $host,
        int $port,
        string $username,
        string $password,
        string $database
    );
    public function fetch(Search $search) : SearchResult;
}