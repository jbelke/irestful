<?php
namespace iRESTful\DSLs\Domain\Projects\Objects\Properties\Types\Parents\Adapters;

interface ParentObjectAdapter {
    public function fromDataToParentObject(array $data);
}
