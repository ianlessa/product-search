<?php

namespace IanLessa\ProductSearch;

use JsonSerializable;

abstract class AbstractValueObject implements JsonSerializable
{
    /**
     * To check the structural equality of value objects,
     * this method should be implemented.
     */
    public function equals($object) : bool
    {
        if (static::class === get_class($object)) {
            return $this->isEqual($object);
        }

        return false;
    }

    abstract protected function isEqual($object) : bool;
}