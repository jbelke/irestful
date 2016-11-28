<?php
namespace iRESTful\Rodson\DSLs\Domain\Projects\Objects;

interface Object {
    public function getName();
    public function getProperties();
    public function hasDatabase();
    public function getDatabase();
    public function hasMethods();
    public function getMethods();
    public function hasCombos();
    public function getCombos();
    public function getPropertyTypes();
    public function getTypes();
}
