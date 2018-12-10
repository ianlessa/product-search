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

    public function searchProduct($params) : SearchResult
    {
        $filters = [];

        $term = $params["q"];

        if ($term !== null) {
            $filters['name'] = $term;
        }

        $filter = $params['filter'];
        if (preg_match('/.{1}:.{1}/', $filter) > 0) {
            $filter = explode(':', $filter);
            $filters[$filter[0]] = $filter[1];
        }

        $sort = null;
        $baseSort = $params['sort'];
        if (preg_match('/.{1}:.{1}/', $baseSort) > 0) {
            $baseSort = explode(':', $baseSort);
            $method = $baseSort[0];
            if (method_exists(Sort::class, $method)) {
                $sort = Sort::$method($baseSort[1]);
            }
        }

        $pagination = new Pagination(
            $params["start_page"],
            $params["per_page"]
        );

        $search = new Search(
            $filters,
            $pagination,
            $sort
        );

        return $this->repository->fetch($search);
    }


}