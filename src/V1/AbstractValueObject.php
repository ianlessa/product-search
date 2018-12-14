<?php

namespace IanLessa\ProductSearch\V1;

use JsonSerializable;

/**
 * The ValueObject Abstraction. It ensures that the value objects can be
 * structurally compared.
 *
 * All the value objects should extend this class.
 *
 * @package IanLessa\ProductSearch\V1
 */
abstract class AbstractValueObject implements JsonSerializable
{
    /**
     * Compares the object types and call the child structural comparison method.
     *
     * @param  mixed $object The object that will be compared.
     * @return bool
     */
    public function equals($object) : bool
    {
        if (static::class === get_class($object)) {
            return $this->isEqual($object);
        }

        return false;
    }

    /**
     * To check the structural equality of value objects,
     * this method should be implemented in this class children.
     *
     * @param  $object
     * @return bool
     */
    abstract protected function isEqual($object) : bool;
}