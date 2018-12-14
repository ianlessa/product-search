<?php

namespace IanLessa\ProductSearch\V1\Interfaces;

use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\SearchResult;

/**
 * The Repository Interface. The abstraction whose the Service layer relies
 * on to do the search.
 *
 * @package IanLessa\ProductSearch\V1\Interfaces
 */
interface RepositoryInterface
{
    /**
     * RepositoryInterface constructor. The connection object that will be used by
     * the repository to do the database related operations. The type was not
     * defined because it varies with the implementation of the concrete repository.
     *
     * @param $connection
     */
    public function __construct($connection);

    /**
     * Fetch the results for a passed Search Object and returns it inside a
     * SearchResult object.
     *
     * @param  Search $search The Search object that will be used to perform the
     *                        query.
     * @return SearchResult The search results.
     */
    public function fetch(Search $search) : SearchResult;
}