<?php
namespace iRESTful\ClassesTests\Infrastructure\Objects;
use iRESTful\ClassesTests\Domain\Test;
use iRESTful\ClassesTests\Domain\Transforms\Transform;
use iRESTful\ClassesTests\Domain\Exceptions\TestException;
use iRESTful\ClassesTests\Domain\Controllers\Controller;
use iRESTful\ClassesTests\Domain\CRUDs\CRUD;

final class ConcreteTest implements Test {
    private $transform;
    private $controller;
    private $crud;
    public function __construct(Transform $transform = null, Controller $controller = null, CRUD $crud = null) {

        $amount = (empty($transform) ? 0 : 1) + (empty($controller) ? 0 : 1) + (empty($crud) ? 0 : 1);
        if ($amount != 1) {
            throw new TestException('There must be either a transform, a CRUD or a controller test.  '.$amount.' given.');
        }

        $this->transform = $transform;
        $this->controller = $controller;
        $this->crud = $crud;
    }

    public function hasTransform() {
        return !empty($this->transform);
    }

    public function getTransform() {
        return $this->transform;
    }

    public function hasCRUD() {
        return !empty($this->crud);
    }

    public function getCRUD() {
        return $this->crud;
    }

    public function hasController() {
        return !empty($this->controller);
    }

    public function getController() {
        return $this->controller;
    }

}
