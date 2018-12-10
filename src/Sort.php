<?php

namespace IanLessa\ProductSearch;

class Sort extends AbstractValueObject
{
    const TYPE_ASC = 'ASC';
    const TYPE_DESC = 'DESC';

    /** @var string */
    private $type;
    /** @var string */
    private $value;

    /**
     * Sort constructor.
     * @param string $type
     * @param string $value
     */
    private function __construct(string $type, string $value)
    {
        $this->setType($type);
        $this->setValue($value);
    }

    static public function asc($value) {
        return new self(self::TYPE_ASC, $value);
    }

    static public function desc($value) {
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
     * @param string $type
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
     * @param string $value
     * @return Sort
     */
    private function setValue(string $value): Sort
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @param Sort $object
     * @return bool
     */
    protected function isEqual($object): bool
    {
        return
            $this->getValue() === $object->getValue() &&
            $this->getType() === $object->getType()
        ;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $obj = new \stdClass();

        $obj->value = $this->getValue();
        $obj->type = $this->getType();

        return $obj;
    }
}