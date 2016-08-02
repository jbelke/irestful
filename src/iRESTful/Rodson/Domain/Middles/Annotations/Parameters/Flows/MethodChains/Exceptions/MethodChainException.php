<?php
namespace iRESTful\Rodson\Domain\Middles\Annotations\Parameters\Flows\MethodChains\Exceptions;

final class MethodChainException extends \Exception {
    const CODE = 1;
    public function __construct($message, \Exception $parentException = null) {
        parent::__construct($message, self::CODE, $parentException);
    }
}
