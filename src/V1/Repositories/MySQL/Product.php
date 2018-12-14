<?php
namespace IanLessa\ProductSearch\V1\Repositories\MySQL;

use IanLessa\ProductSearch\V1\Aggregates\Product as ProductEntity;
use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\SearchResult;
use IanLessa\ProductSearch\V1\Interfaces\RepositoryInterface;
use IanLessa\ProductSearch\V1\Repositories\AbstractRepository;
use PDO;

/**
 * The concrete Product Repository for the MySQL databases.
 *
 * @package IanLessa\ProductSearch\V1\Repositories\MySQL
 */
class Product extends AbstractRepository
{

    /**
     * The connection object created by the Application layer and injected in this
     * Repository.
     *
     * @see RepositoryInterface for more details.
     * @var PDO
     */
    protected $connection;

    /**
     * Do an product search in a MySQL database.
     *
     * @param Search $search The search object that will be used to generate the
     * SQL query.
     * @return SearchResult The search results.
     */
    public function fetch(Search $search) : SearchResult
    {
        // Here we generate the query
        /** @var string $query */
        $query = $this->getBaseQuery();
        $query .= $this->getWhereQuery($search);
        $query .= $this->getSortQuery($search);
        $query .= $this->getPaginationQuery();

        // Then we prepare a statement and bind the correct values into it, based on
        // the Search object.
        /** @var \PDOStatement $statement */
        $statement = $this->connection->prepare($query);

        $this->prepareWhereQuery($statement, $search);
        $this->preparePaginationQuery($statement, $search);

        // Finally we execute the query on database, fetch its results and create
        // a SearchResult with it.
        $statement->execute();

        return $this->formatResults($search, $statement->fetchAll());
    }

    /**
     * Do a query on database to retrieve the last query max results.
     *
     * @return int
     */
    private function getMaxRows() : int
    {
        $result = $this->connection->query("SELECT FOUND_ROWS()")->fetchAll();

        return $result[0][0];
    }

    /**
     * Generate the base product select query.
     * SQL_CALC_FOUND_ROWS will be used to calculate the max results even with the
     * LIMIT defined.
     *
     * @return string
     */
    private function getBaseQuery() : string
    {
        return "
            SELECT SQL_CALC_FOUND_ROWS
              p.*
            FROM 
              product as p 
        ";
    }

    /**
     * Generate the WHERE section of the query.
     * If the search filter array keys aren't Product entity properties with a
     * public getter, the filter will be ignored.
     *
     * @param Search $search
     * @return string
     */
    private function getWhereQuery(Search $search) : string
    {
        $baseQuery = "";

        foreach ($search->getFilters() as $column => $term) {
            $getter = 'get' . ucfirst($column);
            if (method_exists(ProductEntity::class, $getter)) {
                $baseQuery .= "LOWER(p.{$column}) LIKE LOWER( :{$column}_term )  AND ";
            }
        }
        $baseQuery = rtrim($baseQuery, 'AND ');

        if (strlen($baseQuery) > 0) {
            return "WHERE $baseQuery";
        }

        return "";
    }

    /**
     * Bind the search values into the prepared statement.
     *
     * @param \PDOStatement $statement
     * @param Search $search
     */
    private function prepareWhereQuery(\PDOStatement &$statement, Search $search)
    {
        foreach ($search->getFilters() as $column => $term) {
            $statement->bindValue(
                ":{$column}_term",
                "%$term%",
                PDO::PARAM_STR
            );
        }
    }

    /**
     * Get the LIMIT section of the query.
     *
     * @return string
     */
    private function getPaginationQuery() : string
    {
        return "
            LIMIT :limit
            OFFSET :offset
        ";
    }

    /**
     * Bind the Pagination values into the prepared statement.
     *
     * @param \PDOStatement $statement
     * @param Search $search
     */
    private function preparePaginationQuery(\PDOStatement &$statement, Search $search)
    {
        $limit = $search->getPagination()->getPerPage();
        $offset = $limit * $search->getPagination()->getStart();

        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

    /**
     * Generate the SORT section of the query.
     * If the sort column isn't an Product Entity property with an public getter
     * the SORT section will be empty.
     *
     * @param Search $search
     * @return string
     */
    private function getSortQuery(Search $search) : string
    {
        $sort = $search->getSort();
        if ($sort === null) {
            return "";
        }

        $getter = "get" . ucfirst($sort->getValue());
        if (!method_exists(ProductEntity::class, $getter)) {
            return "";
        }

        return "
            ORDER BY {$sort->getValue()} {$sort->getType()}
        ";
    }

    /**
     * @param Search $search
     * @param array $data
     * @return SearchResult
     */
    private function formatResults(Search $search, array $data) : SearchResult
    {
        $results = [];
        foreach ($data as $row) {
            $product = new ProductEntity();
            $product->setId($row['id']);
            $product->setName($row['name']);
            $product->setDescription($row['description']);
            $product->setBrand($row['brand']);

            $results[] = $product;
        }

        return new SearchResult(
            $search,
            $this->getMaxRows(),
            $results
        );
    }

    /**
     * @return string The connection object class that this class expects.
     */
    protected function getConnectionClass(): string
    {
        return PDO::class;
    }
}