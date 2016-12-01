<?php
namespace iRESTful\Rodson\Classes\Infrastructure\Adapters;
use iRESTful\Rodson\Classes\Domain\Constructors\Parameters\Adapters\ParameterAdapter as ConstructorParameterAdapter;
use iRESTful\Rodson\Classes\Domain\Properties\Adapters\PropertyAdapter;
use iRESTful\Rodson\Classes\Domain\Interfaces\Methods\Parameters\Adapters\ParameterAdapter;
use iRESTful\Rodson\DSLs\Domain\Projects\Objects\Object;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Type;
use iRESTful\Rodson\Classes\Infrastructure\Objects\ConcreteConstructorParameter;
use iRESTful\Rodson\DSLs\Domain\Projects\Objects\Properties\Property;
use iRESTful\Rodson\Classes\Domain\Namespaces\Adapters\InterfaceNamespaceAdapter;
use iRESTful\Rodson\Classes\Domain\Constructors\Parameters\Methods\Adapters\MethodAdapter;
use iRESTful\Rodson\Instructions\Domain\Instruction;
use iRESTful\Rodson\Classes\Domain\Constructors\Parameters\Exceptions\ParameterException;
use iRESTful\Rodson\Instructions\Domain\Assignments\Assignment;

final class ConcreteConstructorParameterAdapter implements ConstructorParameterAdapter {
    private $namespaceAdapter;
    private $propertyAdapter;
    private $parameterAdapter;
    private $methodAdapter;
    public function __construct(
        InterfaceNamespaceAdapter $namespaceAdapter,
        PropertyAdapter $propertyAdapter,
        ParameterAdapter $parameterAdapter,
        MethodAdapter $methodAdapter
    ) {
        $this->namespaceAdapter = $namespaceAdapter;
        $this->propertyAdapter = $propertyAdapter;
        $this->parameterAdapter = $parameterAdapter;
        $this->methodAdapter = $methodAdapter;
    }

    public function fromInstructionsToParameters(array $instructions) {

        $propertyName = 'controllerResponseAdapter';
        $namespaceData = explode('\\', 'iRESTful\LeoPaul\Applications\Libraries\Routers\Domain\Controllers\Responses\Adapters\ControllerResponseAdapter');

        $namespace = $this->namespaceAdapter->fromFullDataToNamespace($namespaceData);
        $property = $this->propertyAdapter->fromNameToProperty($propertyName);
        $methodParameter = $this->parameterAdapter->fromDataToParameter([
            'name' => $propertyName,
            'namespace' => $namespace
        ]);

        $parameters = [
            $propertyName => new ConcreteConstructorParameter($property, $methodParameter)
        ];

        foreach($instructions as $oneInstruction) {
            $newParameters = $this->fromInstructionToParameters($oneInstruction);
            if (!empty($newParameters)) {
                $parameters = array_merge($parameters, $newParameters);
            }
        }

        return $parameters;

    }

    public function fromInstructionToParameters(Instruction $instruction) {

        $parameters = [];
        if ($instruction->hasAssignment()) {
            $assignment = $instruction->getAssignment();
            $newParameters = $this->fromInstructionAssignmentToParameters($assignment);
            if (!empty($newParameters)) {
                $parameters = array_merge($parameters, $newParameters);
            }
        }

        if ($instruction->hasMergeAssignments()) {
            $assignments = $instruction->getMergeAssignments();
            $newParameters = $this->fromInstructionAssignmentsToParameters($assignments);
            if (!empty($newParameters)) {
                $parameters = array_merge($parameters, $newParameters);
            }
        }

        if ($instruction->hasAction()) {
            $action = $instruction->getAction();
            $oneParameter = $this->fromInstructionDatabaseActionToParameter($action);
            $parameters[$oneParameter->getProperty()->getName()] = $oneParameter;
        }

        return $parameters;

    }

    public function fromInstructionAssignmentsToParameters(array $assignments) {
        $parameters = [];
        foreach($assignments as $oneAssignment) {
            $newParameters = $this->fromInstructionAssignmentToParameters($oneAssignment);
            if (!empty($newParameters)) {
                $parameters = array_merge($parameters, $newParameters);
            }
        }

        return $parameters;
    }

    public function fromInstructionAssignmentToParameters($assignment) {

        $parameters = [];
        if ($assignment->hasMergedAssignments()) {
            $assignments = $assignment->getMergedAssignments();
            $newParameters = $this->fromInstructionAssignmentsToParameters($assignments);
            if (!empty($newParameters)) {
                $parameters = array_merge($parameters, $newParameters);
            }
        }

        if ($assignment->hasConversion()) {
            $conversion = $assignment->getConversion();

            $propertyName = 'entityAdapterFactory';
            $namespaceData = explode('\\', 'iRESTful\LeoPaul\Objects\Entities\Entities\Domain\Adapters\Factories\EntityAdapterFactory');

            $namespace = $this->namespaceAdapter->fromFullDataToNamespace($namespaceData);
            $property = $this->propertyAdapter->fromNameToProperty($propertyName);
            $methodParameter = $this->parameterAdapter->fromDataToParameter([
                'name' => $propertyName,
                'namespace' => $namespace
            ]);

            $parameters[$propertyName] = new ConcreteConstructorParameter($property, $methodParameter);

            if ($conversion->hasFrom()) {
                $from = $conversion->getFrom();
                if ($from->hasAssignment()) {
                    $assignment = $from->getAssignment();
                    $newParameters = $this->fromInstructionAssignmentToParameters($assignment);
                    if (!empty($newParameters)) {
                        $parameters = array_merge($parameters, $newParameters);
                    }
                }
            }
        }

        if ($assignment->hasDatabase()) {
            $database = $assignment->getDatabase();

            if ($database->hasRetrieval()) {
                $retrieval = $database->getRetrieval();
                $newParameter = $this->fromInstructionDatabaseRetrievalToParameter($retrieval);
                $parameters[$newParameter->getProperty()->getName()] = $newParameter;
            }

            if ($database->hasAction()) {
                $action = $database->getAction();
                $newParameter = $this->fromInstructionDatabaseActionToParameter($action);
                $parameters[$newParameter->getProperty()->getName()] = $newParameter;
            }
        }

        return $parameters;
    }

    public function fromInstructionDatabaseActionToParameter($action) {

        $getNamespaceData = function($action) {

            $entitySetService = explode('\\', 'iRESTful\LeoPaul\Objects\Entities\Entities\Domain\Sets\Services\Factories\EntitySetServiceFactory');
            $entityService = explode('\\', 'iRESTful\LeoPaul\Objects\Entities\Entities\Domain\Services\Factories\EntityServiceFactory');

            $byAssignment = function(Assignment $assignment) use(&$entitySetService, &$entityService) {

                if ($assignment->isMultipleEntities()) {
                    return $entitySetService;
                }

                if ($assignment->isPartialEntitySet()) {
                    //throws
                }

                return $entityService;

            };

            if ($action->hasHttpRequest()) {
                return explode('\\', 'iRESTful\LeoPaul\Objects\Libraries\Https\Applications\Factories\Adapters\HttpApplicationFactoryAdapter');
            }

            if ($action->hasInsert()) {
                $insert = $action->getInsert();
                if ($insert->hasAssignment()) {
                    $assignment = $insert->getAssignment();
                    return $byAssignment($assignment);
                }

                if ($insert->hasAssignments()) {
                    return $entitySetService;
                }

                //throws

            }

            if ($action->hasUpdate()) {
                $updateAssignment = $action->getUpdate()->getUpdated();
                return $byAssignment($updateAssignment);
            }

            if ($action->hasDelete()) {
                $delete = $action->getDelete();
                if ($delete->hasAssignment()) {
                    $assignment = $delete->getAssignment();
                    return $byAssignment($assignment);
                }

                if ($delete->hasAssignments()) {
                    return $entitySetService;
                }
            }

            //throws
        };

        $namespaceData = $getNamespaceData($action);
        $propertyName = lcfirst($namespaceData[count($namespaceData) - 1]);

        $namespace = $this->namespaceAdapter->fromFullDataToNamespace($namespaceData);
        $property = $this->propertyAdapter->fromNameToProperty($propertyName);
        $methodParameter = $this->parameterAdapter->fromDataToParameter([
            'name' => $propertyName,
            'namespace' => $namespace
        ]);

        return new ConcreteConstructorParameter($property, $methodParameter);
    }

    public function fromInstructionDatabaseRetrievalToParameter($retrieval) {

        $getNamespaceData = function($retrieval) {

            $name = '';
            if ($retrieval->hasHttpRequest()) {
                $name = 'iRESTful\LeoPaul\Objects\Libraries\Https\Applications\Factories\Adapters\HttpApplicationFactoryAdapter';
            }

            if ($retrieval->hasEntity()) {
                $name = 'iRESTful\LeoPaul\Objects\Entities\Entities\Domain\Repositories\Factories\EntityRepositoryFactory';
            }

            if ($retrieval->hasMultipleEntities()) {
                $name = 'iRESTful\LeoPaul\Objects\Entities\Entities\Domain\Sets\Repositories\Factories\EntitySetRepositoryFactory';
            }

            if ($retrieval->hasEntityPartialSet()) {
                $name = 'iRESTful\LeoPaul\Objects\Entities\Entities\Domain\Sets\Partials\Repositories\Factories\EntityPartialSetRepositoryFactory';
            }

            if ($retrieval->hasRelatedEntity()) {
                $name = 'iRESTful\LeoPaul\Objects\Entities\Entities\Domain\Relations\Repositories\Factories\EntityRelationRepositoryFactory';
            }

            if (empty($name)) {
                throw new NamespaceException('The given Retrieval object did not have a valid retrieval method.');
            }

            return explode('\\', $name);
        };

        $namespaceData = $getNamespaceData($retrieval);
        $propertyName = lcfirst($namespaceData[count($namespaceData) - 1]);

        $namespace = $this->namespaceAdapter->fromFullDataToNamespace($namespaceData);
        $property = $this->propertyAdapter->fromNameToProperty($propertyName);
        $methodParameter = $this->parameterAdapter->fromDataToParameter([
            'name' => $propertyName,
            'namespace' => $namespace
        ]);

        return new ConcreteConstructorParameter($property, $methodParameter);
    }

    public function fromObjectToParameters(Object $object) {
        $output = [];
        $properties = $object->getProperties();
        foreach($properties as $oneProperty) {
            $output[] = $this->fromPropertyToParameter($oneProperty);
        }

        return $output;
    }

    public function fromPropertyToParameter(Property $property) {

        $getNamespace = function($propertyType) {
            if ($propertyType->hasType()) {
                $type = $propertyType->getType();
                return $this->namespaceAdapter->fromTypeToNamespace($type);
            }

            if ($propertyType->hasObject()) {
                $object = $propertyType->getObject();
                return $this->namespaceAdapter->fromObjectToNamespace($object);
            }

            if ($propertyType->hasParentObject()) {
                $parentObject = $propertyType->getParentObject();
                return $this->namespaceAdapter->fromParentObjectToNamespace($parentObject);
            }

            //throws
        };

        $propertyName = $property->getName();
        $propertyIsOptional = $property->isOptional();
        $propertyType = $property->getType();
        $method = $this->methodAdapter->fromPropertyToMethod($property);
        $classProperty = $this->propertyAdapter->fromNameToProperty($propertyName);

        if ($propertyType->hasPrimitive()) {
            $propertyTypePrimitive = $propertyType->getPrimitive();
            $methodParameter = $this->parameterAdapter->fromDataToParameter([
                'name' => $propertyName,
                'primitive' => $propertyTypePrimitive,
                'is_optional' => $propertyIsOptional
            ]);

            return new ConcreteConstructorParameter($classProperty, $methodParameter, $method);
        }

        $propertyIsArray = $propertyType->isArray();
        $namespace = $getNamespace($propertyType);
        $methodParameter = $this->parameterAdapter->fromDataToParameter([
            'name' => $propertyName,
            'namespace' => $namespace,
            'is_optional' => $propertyIsOptional,
            'is_array' => $propertyIsArray
        ]);

        return new ConcreteConstructorParameter($classProperty, $methodParameter, $method);
    }

    public function fromTypeToParameter(Type $type) {

        $converter = $type->getDatabaseConverter();
        $getPrimitive = function() use(&$converter) {
            if (!$converter->hasFromType()) {
                return null;
            }

            $type = $converter->fromType();
            if (!$type->hasPrimitive()) {
                return null;
            }

            return $type->getPrimitive();
        };

        $getNamespace = function() use(&$converter) {

            if (!$converter->hasFromType()) {
                return null;
            }

            $type = $converter->fromType();
            if (!$type->hasType()) {
                return null;
            }

            $typeType = $type->getType();
            return $typeType->getNamespace();

        };

        $name = $type->getName();
        $classProperty = $this->propertyAdapter->fromNameToProperty($name);
        $methodParameter = $this->parameterAdapter->fromDataToParameter([
            'name' => $name,
            'primitive' => $getPrimitive(),
            'namespace' => $getNamespace()
        ]);

        $method = $this->methodAdapter->fromTypeToMethod($type);
        return new ConcreteConstructorParameter($classProperty, $methodParameter, $method);
    }

}
