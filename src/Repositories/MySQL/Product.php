<?php
namespace IanLessa\ProductSearch\Repositories\MySQL;

use IanLessa\ProductSearch\Aggregates\Brand;
use IanLessa\ProductSearch\Aggregates\Product as ProductEntity;
use IanLessa\ProductSearch\Interfaces\RepositoryInterface;
use IanLessa\ProductSearch\Search;
use IanLessa\ProductSearch\SearchResult;
use IanLessa\ProductSearch\SearchService;
use PDO;

class Product implements RepositoryInterface
{
    /** @var PDO */
    private $pdo;

    public function __construct(
        string $host,
        int $port,
        string $username,
        string $password
    )
    {
        $dsn = "mysql:host=$host;port=$port;dbname=product_search";

        $this->pdo = new PDO($dsn, $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function fetch(Search $search) : SearchResult
    {
        $query = $this->getBaseQuery();
        $query .= $this->getWhereQuery($search);
        $query .= $this->getSortQuery($search);
        $query .= $this->getPaginationQuery();

        $statement = $this->pdo->prepare($query);

        $this->prepareWhereQuery($statement, $search);
        $this->preparePaginationQuery($statement, $search);

        $statement->execute();

        return $this->formatResults($search, $statement->fetchAll());
    }

    private function getBaseQuery() : string
    {
        return "
            SELECT 
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
            $results
        );
    }
}