<?php

namespace IanLessa\ProductSearch\V1;

use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\SearchResult;
use IanLessa\ProductSearch\V1\Interfaces\RepositoryInterface;

class SearchService
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function searchProduct(Search $search) : SearchResult
    {
        return $this->repository->fetch($search);
    }


}