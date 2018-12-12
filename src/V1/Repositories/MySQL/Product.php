<?php
namespace IanLessa\ProductSearch\V1\Repositories\MySQL;

use IanLessa\ProductSearch\V1\Aggregates\Product as ProductEntity;
use IanLessa\ProductSearch\V1\Aggregates\Search;
use IanLessa\ProductSearch\V1\Aggregates\SearchResult;
use IanLessa\ProductSearch\V1\Repositories\AbstractRepository;
use PDO;

class Product extends AbstractRepository
{
    /**
     * @var PDO
     */
    protected $connection;

    public function fetch(Search $search) : SearchResult
    {
        $query = $this->getBaseQuery();
        $query .= $this->getWhereQuery($search);
        $query .= $this->getSortQuery($search);
        $query .= $this->getPaginationQuery();

        $statement = $this->connection->prepare($query);

        $this->prepareWhereQuery($statement, $search);
        $this->preparePaginationQuery($statement, $search);

        $statement->execute();

        return $this->formatResults($search, $statement->fetchAll());
    }

    private function getMaxRows() : int
    {
        $result = $this->connection->query("SELECT FOUND_ROWS()")->fetchAll();

        return $result[0][0];
    }

    private function getBaseQuery() : string
    {
        return "
            SELECT SQL_CALC_FOUND_ROWS
              p.*
            FROM 
              product as p 
        ";
    }

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

    private function getPaginationQuery() : string
    {
        return "
            LIMIT :limit
            OFFSET :offset
        ";
    }

    private function preparePaginationQuery(\PDOStatement &$statement, Search $search)
    {
        $limit = $search->getPagination()->getPerPage();
        $offset = $limit * $search->getPagination()->getStart();

        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    }

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

    protected function getConnectionClass(): string
    {
        return PDO::class;
    }
}