<?php

namespace IanLessa\ProductSearch\V1\Aggregates;

use IanLessa\ProductSearch\V1\AbstractValueObject;

/**
 * Sort Value Object. Holds the attributes related to the sort and the business
 * rules related to them.
 *
 * All the setters are private in order to respect the invariability of
 * a Value Object.
 *
 * @package IanLessa\ProductSearch\V1\Aggregates
 */
class Sort extends AbstractValueObject
{
    /**
     * @const Defines the constant to the Ascending sort type
     */
    const TYPE_ASC = 'ASC';
    /**
     * @const Defines the constant to the Descending sort type
     */
    const TYPE_DESC = 'DESC';

    /**
     * @var string The type of the sort.
     */
    private $type;
    /**
     * @var string The entity attribute that the search will be sort by.
     */
    private $value;

    /**
     * Sort constructor.
     * Since there are specific types of sort, the only way to instantiate this
     * class is by using the static methods provided.
     *
     * @param string $type
     * @param string $value
     */
    private function __construct(string $type, string $value)
    {
        $this->setType($type);
        $this->setValue($value);
    }

    /**
     * Instantiates an Ascending type Sort object.
     *
     * @param  string $value The entity attribute to sort by.
     * @return Sort
     */
    static public function asc(string $value)
    {
        return new self(self::TYPE_ASC, $value);
    }

    /**
     * Instantiates a Descending type Sort object.
     *
     * @param  string $value The entity attribute to sort by.
     * @return Sort
     */
    static public function desc(string $value)
    {
        return new self(self::TYPE_DESC, $value);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param  string $type
     * @return Sort
     */
    private function setType(string $type): Sort
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param  string $value
     * @return Sort
     */
    private function setValue(string $value): Sort
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Do the structural comparison with another Value Object.
     *
     * @param  Sort $object
     * @return bool
     */
    protected function isEqual($object): bool
    {
        return
            $this->getValue() === $object->getValue() &&
            $this->getType() === $object->getType();
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link   http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since  5.4.0
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();

        $obj->value = $this->getValue();
        $obj->type = $this->getType();

        return $obj;
    }
}