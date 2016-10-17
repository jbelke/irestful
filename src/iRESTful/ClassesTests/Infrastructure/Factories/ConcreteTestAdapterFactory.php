<?php
namespace iRESTful\ClassesTests\Infrastructure\Factories;
use iRESTful\ClassesTests\Domain\Adapters\Factories\TestAdapterFactory;
use iRESTful\ClassesTests\Infrastructure\Adapters\ConcreteTestAdapter;
use iRESTful\ClassesTests\Infrastructure\Adapters\ConcreteTestTransformAdapter;
use iRESTful\ClassesTests\Infrastructure\Adapters\ConcreteTestControllerAdapter;
use iRESTful\Classes\Infrastructure\Adapters\ConcreteNamespaceAdapter;
use iRESTful\Classes\Infrastructure\Adapters\ConcreteInterfaceNamespaceAdapter;
use iRESTful\Classes\Infrastructure\Adapters\ConcreteInterfaceMethodParameterTypeAdapter;
use iRESTful\Classes\Infrastructure\Adapters\ConcreteInterfaceMethodParameterAdapter;
use iRESTful\Classes\Infrastructure\Adapters\ConcreteMethodCustomAdapter;
use iRESTful\TestInstructions\Infrastructure\Factories\ConcreteTestInstructionAdapterAdapterFactory;

final class ConcreteTestAdapterFactory implements TestAdapterFactory {
    private $baseNamespaces;
    public function __construct(array $baseNamespaces) {
        $this->baseNamespaces = $baseNamespaces;
    }

    public function create() {

        $testTransformAdapter = new ConcreteTestTransformAdapter($this->baseNamespaces);

        $testInstructionAdapterAdapterFactory = new ConcreteTestInstructionAdapterAdapterFactory();
        $testInstructionAdapterAdapter = $testInstructionAdapterAdapterFactory->create();

        $subInterfaceNamespaceAdapter = new ConcreteNamespaceAdapter($this->baseNamespaces);
        $interfaceNamespaceAdapter = new ConcreteInterfaceNamespaceAdapter($subInterfaceNamespaceAdapter);

        $interfaceMethodParamaterTypeAdapter = new ConcreteInterfaceMethodParameterTypeAdapter();
        $interfaceMethodParameterAdapter = new ConcreteInterfaceMethodParameterAdapter($interfaceNamespaceAdapter, $interfaceMethodParamaterTypeAdapter);
        $customMethodAdapter = new ConcreteMethodCustomAdapter($interfaceMethodParameterAdapter);

        $testControllerAdapter = new ConcreteTestControllerAdapter($testInstructionAdapterAdapter, $customMethodAdapter, $this->baseNamespaces);

        return new ConcreteTestAdapter($testTransformAdapter, $testControllerAdapter);
    }

}
