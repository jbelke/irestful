<?php
namespace iRESTful\Rodson\Domain\Inputs\Objects\Properties\Types;

interface Type {
    public function hasType();
    public function getType();
    public function hasObject();
    public function getObject();
    public function isArray();
}