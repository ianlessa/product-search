<?php

namespace IanLessa\ProductSearch\Interfaces;

use IanLessa\ProductSearch\Search;
use IanLessa\ProductSearch\SearchResult;

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