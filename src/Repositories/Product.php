<?php
namespace IanLessa\ProductSearch\Repositories;

use IanLessa\ProductSearch\Aggregates\Brand;
use IanLessa\ProductSearch\Aggregates\Product as ProductEntity;
use IanLessa\ProductSearch\Search;
use IanLessa\ProductSearch\SearchResult;
use PDO;

class Product
{
    /** @var PDO */
    private $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=product_search';
        $username = 'ian';
        $password = 'root';

        $this->pdo = new PDO($dsn, $username, $password);
    }

    public function fetch(Search $search) : SearchResult
    {
        $query = $this->getBaseQuery();//"$baseQuery $whereQuery $paginationQuery";
        $query .= $this->getWhereQuery($search);
        $query .= $this->getPaginationQuery($search);

        $statement = $this->pdo->prepare($query);

        $statement->execute();

        return $this->formatResults($search, $statement->fetchAll());
    }

    private function getBaseQuery() : string
    {
        return "
            SELECT 
              p.*, 
              b.name as brand,
              b.id as brand_id 
            FROM 
              product as p
                INNER JOIN 
                  brand as b ON p.brand_id = b.id            
        ";
    }

    private function getMatchQueryForName(string $term) : string
    {
        return "LOWER(p.name) LIKE LOWER('%$term%')";
    }

    private function getMatchQueryForDescription(string $term) : string
    {
        return "LOWER(p.description) LIKE LOWER('%$term%')";
    }

    private function getWhereQuery(Search $search) : string
    {
        $sections = [];
        $matchQuery = '';
        foreach ($search->getMatches() as $match) {
            $handler = "getMatchQueryFor" . ucfirst($match);
            if (method_exists($this, $handler)) {
                $matchQuery .= $this->$handler($search->getTerm()) . " OR ";
            }
        }
        $matchQuery = rtrim($matchQuery, 'OR ');
        $sections[] = $matchQuery;

        $filterQuery = '';
        foreach ($search->getFilters() as $column => $filter)
        {
            $filterQuery .= "LOWER(b.name) like LOWER('%{$filter}%') AND";
        }
        $filterQuery = rtrim($filterQuery, 'AND');
        $sections[] = $filterQuery;

        $sections = array_filter($sections, function($section){
           return strlen($section) > 0;
        });

        $matchQuery = '';
        foreach ($sections as $section) {
            $matchQuery .= "($section) AND ";
        }
        $matchQuery = rtrim($matchQuery, "AND ");
        if (strlen($matchQuery) > 0) {
            return "WHERE $matchQuery";
        }

        return "";
    }

    private function getPaginationQuery(Search $search) : string
    {
        $limit = $search->getPagination()->getPerPage();
        $offset = $limit * $search->getPagination()->getStart();

        return "
            LIMIT $limit
            OFFSET $offset
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

            $brand = new Brand($row['brand']);
            $brand->setId($row['brand_id']);

            $product->setBrand($brand);

            $results[] = $product;
        }

        return new SearchResult(
            $search,
            $results
        );
    }
}