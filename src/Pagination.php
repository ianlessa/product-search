<?php

namespace IanLessa\ProductSearch;

use IanLessa\ProductSearch\Exceptions\InvalidParamException;

class Pagination extends AbstractValueObject
{
    /** @var int */
    private $start;
    /** @var int */
    private $perPage;

    public function __construct($start, $perPage)
    {
        $this->setStart($start);
        $this->setPerPage($perPage);
    }

    static public function default()
    {
        return new self(5, 1);
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @param int $start
     * @return Pagination
     */
    private function setStart(int $start): Pagination
    {
        if ($start < 1) {
            throw new InvalidParamException("Start should be greater than 1!", $start);
        }

        $this->start = $start;
        return $this;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     * @return Pagination
     */
    private function setPerPage(int $perPage): Pagination
    {
        if ($perPage < 0) {
            throw new InvalidParamException("PerPage should be greater than 0!", $perPage);
        }

        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @param Pagination $object
     * @return bool
     */
    protected function isEqual($object) : bool
    {
        return
            $this->getStart() === $object->getStart() &&
            $this->getPerPage() === $object->getPerPage()
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
        $object = new \stdClass;
        $object->start = $this->getStart();
        $object->perPage = $this->getPerPage();

        return $object;
    }
}