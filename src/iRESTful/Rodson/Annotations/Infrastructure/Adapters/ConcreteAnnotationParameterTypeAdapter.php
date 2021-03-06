<?php
namespace iRESTful\Rodson\Annotations\Infrastructure\Adapters;
use iRESTful\Rodson\Annotations\Domain\Parameters\Types\Adapters\TypeAdapter;
use iRESTful\Rodson\DSLs\Domain\Projects\Objects\Properties\Property;
use iRESTful\Rodson\Annotations\Infrastructure\Objects\ConcreteAnnotationParameterType;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Adapters\DatabaseTypeAdapter;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Type;

final class ConcreteAnnotationParameterTypeAdapter implements TypeAdapter {
    private $databaseTypeAdapter;
    public function __construct(DatabaseTypeAdapter $databaseTypeAdapter) {
        $this->databaseTypeAdapter = $databaseTypeAdapter;
    }

    public function fromTypeToType(Type $type) {
        $databaseType = $type->getDatabaseType();
        return new ConcreteAnnotationParameterType(false, false, $databaseType, $default);
    }

    public function fromObjectPropertyToType(Property $objectProperty) {

        $isUnique = $objectProperty->isUnique();
        $isKey = $objectProperty->isKey();

        $default = null;
        if ($objectProperty->hasDefault()) {
            $default = $objectProperty->getDefault();
        }

        $databaseType = null;
        $objectPropertyType = $objectProperty->getType();
        if ($objectPropertyType->hasType()) {
            $type = $objectPropertyType->getType();
            $databaseType = $type->getDatabaseType();
        }

        if ($objectPropertyType->hasObject()) {
            $object = $objectPropertyType->getObject();
            if ($object->hasDatabase()) {
                $databaseType = $this->databaseTypeAdapter->fromDataToDatabaseType([
                    'name' => 'binary',
                    'specific' => 128
                ]);
            }
        }

        if ($objectPropertyType->hasParentObject()) {
            $databaseType = $this->databaseTypeAdapter->fromDataToDatabaseType([
                'name' => 'binary',
                'specific' => 128
            ]);
        }

        if ($objectPropertyType->hasPrimitive()) {
            $objectPropertyPrimitiveName = $objectPropertyType->getPrimitive()->getName();
            $databaseType = $this->databaseTypeAdapter->fromDataToDatabaseType([
                'name' => $objectPropertyPrimitiveName,
                'max' => 255
            ]);
        }

        return new ConcreteAnnotationParameterType($isUnique, $isKey, $databaseType, $default);

    }

}
