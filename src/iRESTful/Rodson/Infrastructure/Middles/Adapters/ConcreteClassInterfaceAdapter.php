<?php
namespace iRESTful\Rodson\Infrastructure\Middles\Adapters;
use iRESTful\Rodson\Domain\Middles\Classes\Interfaces\Adapters\InterfaceAdapter;
use iRESTful\Rodson\Domain\Inputs\Objects\Object;
use iRESTful\Rodson\Domain\Inputs\Types\Type;
use iRESTful\Rodson\Domain\Middles\Classes\Interfaces\Methods\Adapters\MethodAdapter;
use iRESTful\Rodson\Domain\Middles\Namespaces\Adapters\NamespaceAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Objects\ConcreteClassInterface;

final class ConcreteClassInterfaceAdapter implements InterfaceAdapter {
    private $namespaceAdapter;
    private $methodAdapter;
    public function __construct(NamespaceAdapter $namespaceAdapter, MethodAdapter $methodAdapter) {
        $this->namespaceAdapter = $namespaceAdapter;
        $this->methodAdapter = $methodAdapter;
    }

    public function fromObjectToInterface(Object $object) {

        $isEntity = $object->hasDatabase();
        $methods = $this->methodAdapter->fromObjectToMethods($object);
        $namespace = $this->namespaceAdapter->fromObjectToNamespace($object);

        $name = $namespace->getName();
        return new ConcreteClassInterface($name, $methods, $namespace, $isEntity);
    }

    public function fromTypeToInterface(Type $type) {

        $method = $this->methodAdapter->fromTypeToMethod($type);
        $namespace = $this->namespaceAdapter->fromTypeToNamespace($type);

        $name = $namespace->getName();
        return new ConcreteClassInterface($name, [$method], $namespace, false);

    }

    public function fromTypeToAdapterInterface(Type $type) {

        $methods = $this->methodAdapter->fromTypeToAdapterMethods($type);
        $namespace = $this->namespaceAdapter->fromTypeToAdapterNamespace($type);

        $name = $namespace->getName();
        return new ConcreteClassInterface($name, $methods, $namespace, false);
    }

}