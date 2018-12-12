<?php

namespace IanLessa\ProductSearch\V1;

use IanLessa\ProductSearch\V1\Aggregates\Pagination;
use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\SearchResult;
use IanLessa\ProductSearch\V1\Aggregates\Sort;
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

    public function createSearchFromGet(array $params) : Search
    {
        try {
            $filters = [];

            $term = $params["q"] ?? null;

            if ($term !== null) {
                $filters['name'] = $term;
            }

            $filter = $params['filter'] ?? null;
            if (preg_match('/.{1}:.{1}/', $filter) > 0) {
                $filter = explode(':', $filter);
                $filters[$filter[0]] = $filter[1];
            }

            $sort = null;
            $baseSort = $params['sort'] ?? null;
            if (preg_match('/.{1}:.{1}/', $baseSort) > 0) {
                $baseSort = explode(':', $baseSort);
                $method = $baseSort[0];
                if (method_exists(Sort::class, $method)) {
                    $sort = Sort::$method($baseSort[1]);
                }
            }

            $pagination = new Pagination(
                $params["start_page"] ?? null,
                $params["per_page"] ?? null
            );

            return new Search(
                $filters,
                $pagination,
                $sort
            );
        }catch(\Throwable $e) {
            return new Search;
        }
    }
}