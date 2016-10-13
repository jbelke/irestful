<?php
namespace iRESTful\ClassesObjects\Domain;

interface Object {
    public function getObject();
    public function getNamespace();
    public function getInterface();
    public function getConstructor();
    public function hasCustomMethods();
    public function getCustomMethods();
}