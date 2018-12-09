<?php

namespace IanLessa\ProductSearch\Database;

use IanLessa\ProductSearch\AbstractEntity;
use IanLessa\ProductSearch\Interfaces\DatabaseInterface;
use PDO;

class MySQL implements DatabaseInterface
{
    public function findById(string $id): AbstractEntity
    {
        $dsn = 'mysql:host=localhost;dbname=productsearch';
        $username = 'username';
        $password = 'password';
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );

        $dbh = new PDO($dsn, $username, $password, $options);



    }
}