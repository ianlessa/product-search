<?php

namespace IanLessa\ProductSearch;

use IanLessa\ProductSearch\Interfaces\RepositoryInterface;

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