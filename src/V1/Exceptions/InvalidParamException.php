<?php
namespace IanLessa\ProductSearch\V1\Exceptions;

use Throwable;

class InvalidParamException extends \Exception
{
    public function __construct(string $message, $value)
    {
        $message .= " Passed value: $value";
        parent::__construct($message, 0, null);
    }
}