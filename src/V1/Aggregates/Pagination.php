<?php

namespace IanLessa\ProductSearch\V1\Aggregates;

use IanLessa\ProductSearch\V1\AbstractValueObject;
use IanLessa\ProductSearch\V1\Exceptions\InvalidParamException;

/**
 * The Pagination Value Object.
 * All the setters are private in order to respect the invariability of
 * a Value Object.
 *
 * @package IanLessa\ProductSearch\V1\Aggregates
 */
class Pagination extends AbstractValueObject
{
    /**
     * @const int Default value for the pagination start page.
     */
    const DEFAULT_START = 0;
    /**
     * @const int Default value for the pagination results per page.
     */
    const DEFAULT_PERPAGE = 5;

    /**
     * @var int 
     */
    private $start;
    /**
     * @var int 
     */
    private $perPage;

    /**
     * Pagination constructor.
     *
     * Omitting any of the parameters make it be set to the default value defined
     * by the constants.
     *
     * @param  ?int $start
     * @param  ?int $perPage
     * @throws InvalidParamException
     */
    public function __construct($start = null, $perPage = null)
    {
        $this->setStart($start ?? self::DEFAULT_START);
        $this->setPerPage($perPage ?? self::DEFAULT_PERPAGE);
    }

    /**
     * Creates an Pagination object with the default settings.
     *
     * @return Pagination
     * @throws InvalidParamException
     */
    static public function default()
    {
        return new self(
            self::DEFAULT_START,
            self::DEFAULT_PERPAGE
        );
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }


    /**
     * Pagination Start Setter.
     * Holds the business rule related to the start property.
     *
     * @param  int $start
     * @return Pagination
     * @throws InvalidParamException
     */
    private function setStart(int $start): Pagination
    {
        if ($start < 0) {
            throw new InvalidParamException("Start should be at least 0!", $start);
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
     * Pagination PerPage Setter.
     * Holds the business rule related to the perPage property.
     *
     * @param  int $perPage
     * @return Pagination
     * @throws InvalidParamException
     */
    private function setPerPage(int $perPage): Pagination
    {
        if ($perPage <= 0) {
            throw new InvalidParamException("PerPage should be greater than 0!", $perPage);
        }

        $this->perPage = $perPage;
        return $this;
    }

    /**
     * Do the structural comparison with another Value Object.
     *
     * @param  Pagination $object
     * @return bool
     */
    protected function isEqual($object) : bool
    {
        return
            $this->getStart() === $object->getStart() &&
            $this->getPerPage() === $object->getPerPage();
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
        $object = new \stdClass;
        $object->start = $this->getStart();
        $object->perPage = $this->getPerPage();

        return $object;
    }
}