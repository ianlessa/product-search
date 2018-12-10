<?php

namespace IanLessa\ProductSearch;

use JsonSerializable;

abstract class AbstractEntity implements JsonSerializable
{
    /**
     * @var string 
     */
    protected $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param  string $id
     * @return AbstractEntity
     */
    public function setId(string $id): AbstractEntity
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param  AbstractEntity $entity
     * @return bool
     */
    public function equals(AbstractEntity $entity) : bool
    {
        return $this->id ===$entity->getId();
    }
}