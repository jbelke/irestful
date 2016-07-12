<?php
namespace iRESTful\Rodson\Domain\Inputs\Adapters\Exceptions;

final class AdapterException extends \Exception {
    const CODE = 1;
    public function __construct($message, \Exception $parentException = null) {
        parent::__construct($message, self::CODE, $parentException);
    }
}