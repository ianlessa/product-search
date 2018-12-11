<?php

namespace IanLessa\ProductSearch\Test\Integration;

use PDO;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;

abstract class AbstractBaseIntegrationTest extends TestCase
{
    /**
     * @var PDO
     */
    protected $pdo;
    /** @var array */
    protected $config;

    use TestCaseTrait;

    /**
     * Returns the test database connection.
     *
     * @return Connection
     */
    protected function getConnection()
    {
        return $this->createDefaultDBConnection($this->pdo, 'product_search_test');
    }

    /**
     * Returns the test dataset.
     *
     * @return IDataSet
     */
    protected function getDataSet()
    {
        return $this->createMySQLXMLDataSet('Test/Integration/mockData.xml');
    }

    public function setUp()
    {
        $config = [
            "DB_HOST" => "",
            "DB_PORT" => "",
            "DB_DATABASE" => "",
            "DB_USERNAME" => "",
            "DB_PASSWORD" => ""
        ];

        foreach ($config as $key => $value) {
            $config[$key] = $GLOBALS[$key];
        }

        $this->config = $config;

        $host = $config['DB_HOST'] ?? 'localhost';
        $port = $config['DB_PORT'] ?? '3306';
        $database = $config['DB_DATABASE'] ?? 'product_search';
        $username = $config['DB_USERNAME'] ?? 'root';
        $password = $config['DB_PASSWORD'] ?? 'root';
        $dsn = "mysql:host=$host;port=$port;dbname=$database";

        $this->pdo = new PDO($dsn, $username, $password);

        $this->pdo->exec('
            create table if not exists product
            (
              id          int auto_increment
                primary key,
              name        varchar(45) not null,
              brand       varchar(25) not null,
              description text        not null
            );
        ');
    }
}