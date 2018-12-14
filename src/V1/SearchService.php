<?php

namespace IanLessa\ProductSearch\V1;

use IanLessa\ProductSearch\V1\Aggregates\Pagination;
use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\SearchResult;
use IanLessa\ProductSearch\V1\Aggregates\Sort;
use IanLessa\ProductSearch\V1\Interfaces\RepositoryInterface;

/**
 * The Product Search Service. Provides the product searching services.
 *
 * Holds the business processes related to product search.
 *
 * @package IanLessa\ProductSearch\V1
 */
class SearchService
{
    /**
     * Holds the concrete Repository class that will be used on the searchs.
     *
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * SearchService constructor.
     *
     * The concrete Product Repository should be instantiated by the Application
     * layer and injected in this class through this constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Do the product search and return its results.
     *
     * @param  Search $search The Search object that will be used in the search.
     * @return SearchResult The search results.
     */
    public function searchProduct(Search $search) : SearchResult
    {
        return $this->repository->fetch($search);
    }

    /**
     * Create a Search object based on the parameters passed in.
     *
     * The Application layer should prepare those parameters in a array format.
     * Given the query: q=term&filter=property:value&sort=type:property, the array
     * should be:
     *
     * [
     *  'q' => 'term',
     *  'filter' => 'property:value',
     *  'sort' => 'type:property'
     * ]
     *
     * In other words, the Application layer just have to explode the query by the
     * '&' and pass it to this method.
     *
     * Any invalid params will be ignored. In case of InvalidParamException, this
     * method will return a default Search object.
     *
     * @param  array $params
     * @return Search
     */
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