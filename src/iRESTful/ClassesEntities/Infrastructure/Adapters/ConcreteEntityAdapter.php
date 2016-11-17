<?php
namespace iRESTful\ClassesEntities\Infrastructure\Adapters;
use iRESTful\ClassesEntities\Domain\Adapters\EntityAdapter;
use iRESTful\DSLs\Domain\Projects\Objects\Object;
use iRESTful\Classes\Domain\Namespaces\Adapters\ClassNamespaceAdapter;
use iRESTful\Classes\Domain\Interfaces\Adapters\InterfaceAdapter;
use iRESTful\Classes\Domain\Constructors\Adapters\ConstructorAdapter;
use iRESTful\Classes\Domain\CustomMethods\Adapters\CustomMethodAdapter;
use iRESTful\ClassesEntities\Infrastructure\Objects\ConcreteEntity;
use iRESTful\ClassesEntities\Domain\Exceptions\EntityException;
use iRESTful\DSLs\Domain\Projects\Objects\Entities\Entity;

final class ConcreteEntityAdapter implements EntityAdapter {
    private $namespaceAdapter;
    private $interfaceAdapter;
    private $constructorAdapter;
    private $customMethodAdapter;
    public function __construct(
        ClassNamespaceAdapter $namespaceAdapter,
        InterfaceAdapter $interfaceAdapter,
        ConstructorAdapter $constructorAdapter,
        CustomMethodAdapter $customMethodAdapter
    ) {
        $this->namespaceAdapter = $namespaceAdapter;
        $this->interfaceAdapter = $interfaceAdapter;
        $this->constructorAdapter = $constructorAdapter;
        $this->customMethodAdapter = $customMethodAdapter;
    }

    public function fromDSLEntitiesToEntities(array $entities) {
        $output = [];
        foreach($entities as $oneEntity) {
            $output[] = $this->fromDSLEntityToEntity($oneEntity);
        }

        return $output;
    }

    public function fromDSLEntityToEntity(Entity $entity) {

        $object = $entity->getObject();
        $namespace = $this->namespaceAdapter->fromObjectToNamespace($object);
        $interface = $this->interfaceAdapter->fromObjectToInterface($object);
        $constructor = $this->constructorAdapter->fromObjectToConstructor($object);
        $customMethods = $this->customMethodAdapter->fromObjectToCustomMethods($object);

        return new ConcreteEntity(
            $entity,
            $namespace,
            $interface,
            $constructor,
            $customMethods
        );
    }

}
