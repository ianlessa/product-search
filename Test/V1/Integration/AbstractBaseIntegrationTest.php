<?php

namespace IanLessa\ProductSearch\Test\V1\Integration;

use PDO;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use stdClass;

abstract class AbstractBaseIntegrationTest extends TestCase
{
    /**
     * @var PDO
     */
    protected $pdo;
    /** @var array */
    protected $config;
    /** @var stdClass */
    protected $expectedData;

    use TestCaseTrait;

    /**
     * Returns the test database connection.
     *
     * @return Connection
     */
    protected function getConnection()
    {
        if ($this->pdo === null) {
            $this->createPdo();
        }
        return $this->createDefaultDBConnection($this->pdo, 'product_search_test');
    }

    /**
     * Returns the test dataset.
     *
     * @return IDataSet
     */
    protected function getDataSet()
    {
        $path = 'Test/V1/Integration/mockData.xml';
        if (!file_exists($path)) {
            $path = '../mockData.xml';
        }
        return $this->createMySQLXMLDataSet($path);
    }

    public function setUp()
    {
        $expectedDataPath = 'Test/V1/Integration/expectedResults.json';
        if (!file_exists($expectedDataPath)) {
            $expectedDataPath = '../expectedResults.json';
        }

        $expectedData = file_get_contents($expectedDataPath);
        $this->expectedData = json_decode($expectedData);

        $path = 'Test/V1/Integration/mockData.xml';
        if (!file_exists($path)) {
            $path = '../mockData.xml';
        }
        return $this->createMySQLXMLDataSet($path);


        $this->setConfig();
        $this->createPdo();
    }

    protected function setConfig()
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
    }

    protected function createPdo()
    {
        $config = $this->config;
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