<?php
namespace iRESTful\Rodson\Domain\Middles\Annotations\Exceptions;

final class AnnotationException extends \Exception {
    const CODE = 1;
    public function __construct($message, \Exception $parentException = null) {
        parent::__construct($message, self::CODE, $parentException);
    }
}