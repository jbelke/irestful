<?php
namespace iRESTful\Rodson\Rodson\Domain\Outputs\Methods\Returns\Adapters;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Type;
use iRESTful\Rodson\DSLs\Domain\Projects\Objects\Properties\Types\Type as PropertyType;
use iRESTful\Rodson\DSLs\Domain\Projects\Objects\Object;

interface ReturnedInterfaceAdapter {
    public function fromDataToReturnedInterfaces(array $data);
    public function fromDataToReturnedInterface(array $data);
    public function fromTypeToReturnedInterface(Type $type);
    public function fromObjectToReturnedInterface(Object $object);
    public function fromPropertyTypeToReturnedInterface(PropertyType $type);
}
