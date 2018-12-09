<?php
namespace IanLessa\ProductSearch\Repositories;

use IanLessa\ProductSearch\Aggregates\Brand;
use IanLessa\ProductSearch\Aggregates\Product as ProductEntity;
use IanLessa\ProductSearch\Interfaces\DatabaseInterface;
use IanLessa\ProductSearch\Pagination;
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
        $baseQuery = "
            SELECT 
              p.*, 
              b.name as brand,
              b.id as brand_id 
            FROM 
              product AS p 
                INNER JOIN 
                  brand AS b ON p.brand_id = b.id            
        ";

        $limit = $search->getPagination()->getPerPage();
        $offset = $limit * $search->getPagination()->getStart();

        $paginationQuery = "
            LIMIT $limit
            OFFSET $offset
        ";

        $query = "$baseQuery $paginationQuery";

        $statement = $this->pdo->prepare($query);

        $statement->execute();
        $data = $statement->fetchAll();


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

        $results = new SearchResult(
            $search,
            $results
        );

        return $results;
    }

    /** @todo remove me! */
    public function __call($name, $arguments)
    {
    }
}