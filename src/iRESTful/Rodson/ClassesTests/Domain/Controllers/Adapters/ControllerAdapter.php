<?php
namespace iRESTful\Rodson\ClassesTests\Domain\Controllers\Adapters;

interface ControllerAdapter {
    public function fromDataToControllers(array $data);
    public function fromDataToController(array $data);
}
