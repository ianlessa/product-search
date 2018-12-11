<?php
/**
 * Created by PhpStorm.
 * User: ian
 * Date: 11/12/18
 * Time: 11:09
 */

namespace IanLessa\ProductSearch\Repositories;


use IanLessa\ProductSearch\Aggregates\Search;
use IanLessa\ProductSearch\Aggregates\SearchResult;
use IanLessa\ProductSearch\Interfaces\RepositoryInterface;
use Exception;

abstract class AbstractRepository implements RepositoryInterface
{
    protected $connection;

    public function __construct($connection)
    {
        $connectionClass = get_class($connection);
        $expectedClass = $this->getConnectionClass();
        if (get_class($connection) !== $this->getConnectionClass()) {
            throw new Exception("Invalid Connection object! Expected: $expectedClass; Actual: $connectionClass");
        }
        $this->connection = $connection;
    }

    abstract protected function getConnectionClass() : string;
}