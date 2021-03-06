<?php
namespace iRESTful\Rodson\ConfigurationsComposers\Domain\Exceptions;

final class ComposerException extends \Exception {
    const CODE = 1;
    public function __construct($message, \Exception $parentException = null) {
        parent::__construct($message, self::CODE, $parentException);
    }
}
