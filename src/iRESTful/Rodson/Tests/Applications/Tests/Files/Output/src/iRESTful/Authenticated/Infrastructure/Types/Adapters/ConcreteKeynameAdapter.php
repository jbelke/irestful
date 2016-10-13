<?php
namespace iRESTful\Authenticated\Infrastructure\Types\Adapters;
use iRESTful\Authenticated\Domain\Types\Keynames\Adapters\KeynameAdapter;

final class ConcreteKeynameAdapter implements KeynameAdapter {
    

    public function __construct() {
        
    }

            public function fromStringToKeyname($value) {
            return new \iRESTful\Authenticated\Infrastructure\Types\ConcreteKeyname($value);
        }
    
}