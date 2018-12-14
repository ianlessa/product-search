<?php

namespace IanLessa\ProductSearch\V1;

use JsonSerializable;

/**
 * The Entity Abstraction. All the aggregate roots that are entities should extend
 * this class.
 *
 * Holds the business rules related to entities.
 *
 * @package IanLessa\ProductSearch\V1
 */
abstract class AbstractEntity implements JsonSerializable
{
    /**
     * The entity primary key. Since the primary key data type
     * varies with the database layer implementation, its type is string to make
     * the entity decoupled from concrete repositories.
     *
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
     * Do the identity comparison with another Entity.
     *
     * @param  AbstractEntity $entity
     * @return bool
     */
    public function equals(AbstractEntity $entity) : bool
    {
        return $this->id === $entity->getId();
    }
}