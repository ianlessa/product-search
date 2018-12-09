<?php
namespace IanLessa\ProductSearch\Repositories;

use IanLessa\ProductSearch\Aggregates\Product as ProductEntity;
use IanLessa\ProductSearch\Interfaces\DatabaseInterface;
use PDO;

class Product
{
    /** @var DatabaseInterface */
    private $database;

    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    public function find(string $id) : ProductEntity
    {
        $dsn = 'mysql:host=localhost;dbname=product_search';
        $username = 'ian';
        $password = 'root';

        $dbh = new PDO($dsn, $username, $password);

        $stmt = $dbh->prepare("
            SELECT 
              p.*, 
              b.name as brand 
            FROM 
              product AS p 
                INNER JOIN 
                  brand AS b ON p.brand_id = b.id 
            WHERE 
              p.id = :product_id
        ");

        $stmt->bindParam(":product_id", $id, PDO::PARAM_INT );

        $stmt->execute();

        $result = $stmt->fetch();

        $product = new ProductEntity();
        $product->setId($result['id']);
        $product->setName($result['name']);
        $product->setDescription($result['description']);

        return $product;
    }

    public function search() : array
    {
        $dsn = 'mysql:host=localhost;dbname=product_search';
        $username = 'ian';
        $password = 'root';

        $dbh = new PDO($dsn, $username, $password);

        $stmt = $dbh->prepare("
            SELECT 
              p.*, 
              b.name as brand 
            FROM 
              product AS p 
                INNER JOIN 
                  brand AS b ON p.brand_id = b.id            
        ");

        $stmt->bindParam(":product_id", $id, PDO::PARAM_INT );

        $stmt->execute();

        $data = $stmt->fetchAll();

        $results = [];
        foreach ($data as $row) {
            $product = new ProductEntity();
            $product->setId($row['id']);
            $product->setName($row['name']);
            $product->setDescription($row['description']);

            $results[] = $product;
        }

        return $results;
    }
}