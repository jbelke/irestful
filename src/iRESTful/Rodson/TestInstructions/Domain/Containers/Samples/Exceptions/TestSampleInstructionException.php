<?php
namespace iRESTful\Rodson\TestInstructions\Domain\Containers\Samples\Exceptions;

final class TestSampleInstructionException extends \Exception {
    const CODE = 1;
    public function __construct($message, \Exception $parentException = null) {
        parent::__construct($message, self::CODE, $parentException);
    }
}
