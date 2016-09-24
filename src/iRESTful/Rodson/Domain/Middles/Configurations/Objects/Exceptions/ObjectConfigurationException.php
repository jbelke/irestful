<?php
namespace iRESTful\Rodson\Domain\Middles\Configurations\Objects\Exceptions;

final class ObjectConfigurationException extends \Exception {
    const CODE = 1;
    public function __construct($message, \Exception $parentException = null) {
        parent::__construct($message, self::CODE, $parentException);
    }
}
