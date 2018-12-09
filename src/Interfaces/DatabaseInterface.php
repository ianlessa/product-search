<?php

namespace IanLessa\ProductSearch\Interfaces;

use IanLessa\ProductSearch\AbstractEntity;

interface DatabaseInterface
{
    public function findById(string $id) : AbstractEntity;
}