<?php
namespace iRESTful\Rodson\Classes\Infrastructure\Objects;
use iRESTful\Rodson\Classes\Domain\Constructors\Parameters\Methods\Method;
use iRESTful\Rodson\Classes\Domain\Constructors\Parameters\Methods\Exceptions\MethodException;

final class ConcreteConstructorParameterMethod implements Method {
    private $name;
    private $subMethod;
    public function __construct($name, Method $subMethod = null) {

        if (empty($subMethod)) {
            $subMethod = null;
        }

        if (empty($name) || !is_string($name)) {
            throw new MethodException('The name must be a non-empty string.');
        }

        $this->name = $name;
        $this->subMethod = $subMethod;
    }

    public function getName() {
        return $this->name;
    }

    public function hasSubMethod() {
        return !empty($this->subMethod);
    }

    public function getSubMethod() {
        return $this->subMethod;
    }
}
