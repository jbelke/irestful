<?php
namespace iRESTful\Rodson\Domain\Inputs\Projects\Databases\Credentials;

interface Credentials {
    public function getUsername();
    public function hasPassword();
    public function getPassword();
    public function getData();
}
