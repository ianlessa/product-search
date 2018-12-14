<?php

namespace IanLessa\ProductSearch\V1\Repositories;

use IanLessa\ProductSearch\V1\Interfaces\RepositoryInterface;
use Exception;

/**
 * The Repository parent class. Holds the business rules related to the repositories.
 *
 * @package IanLessa\ProductSearch\V1\Repositories
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * The database connection object.
     *
     * @see RepositoryInterface constructor for more details.
     * @var mixed
     */
    protected $connection;

    /**
     * AbstractRepository constructor.
     * It ensures that the connection object passed in is of the type expected by
     * the concrete child repository.
     *
     * @see    RepositoryInterface for more details.
     * @param  mixed $connection The connection object.
     * @throws Exception If the connection object isn't of the expected type, this
     * will be thrown.
     */
    public function __construct($connection)
    {
        $connectionClass = get_class($connection);
        $expectedClass = $this->getConnectionClass();
        if (get_class($connection) !== $this->getConnectionClass()) {
            throw new Exception(
                "Invalid Connection object! " .
                "Expected: $expectedClass; Actual: $connectionClass"
            );
        }
        $this->connection = $connection;
    }

    /**
     * @return string The connection object class expected by the child class.
     */
    abstract protected function getConnectionClass() : string;
}