<?php
namespace iRESTful\Rodson\Classes\Infrastructure\Adapters;
use iRESTful\Rodson\Classes\Domain\Interfaces\Methods\Parameters\Types\Adapters\TypeAdapter;
use iRESTful\Rodson\Classes\Infrastructure\Objects\ConcreteInterfaceMethodParameterType;

final class ConcreteInterfaceMethodParameterTypeAdapter implements TypeAdapter {

    public function __construct() {

    }
    
    public function fromDataToType(array $data) {

        $isArray = false;
        if (isset($data['is_array'])) {
            $isArray = (bool) $data['is_array'];
        }

        $namespace = null;
        if (isset($data['namespace'])) {
            $namespace = $data['namespace'];
        }

        $primitive = null;
        if (isset($data['primitive'])) {
            $primitive = $data['primitive'];
        }

        return new ConcreteInterfaceMethodParameterType($isArray, $namespace, $primitive);

    }

}
