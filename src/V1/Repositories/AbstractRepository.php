<?php

namespace IanLessa\ProductSearch\V1\Repositories;

use IanLessa\ProductSearch\V1\Interfaces\RepositoryInterface;
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