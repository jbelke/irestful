<?php
namespace iRESTful\Rodson\Domain\Inputs\Databases;

interface Database {
    public function getName();
    public function hasRelational();
    public function getRelational();
    public function hasRESTAPI();
    public function getRESTAPI();
}