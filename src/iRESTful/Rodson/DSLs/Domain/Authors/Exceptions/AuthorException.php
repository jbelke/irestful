<?php
namespace iRESTful\Rodson\DSLs\Domain\Authors\Exceptions;

final class AuthorException extends \Exception {
    const CODE = 1;
    public function __construct($message, \Exception $parentException = null) {
        parent::__construct($message, self::CODE, $parentException);
    }
}
