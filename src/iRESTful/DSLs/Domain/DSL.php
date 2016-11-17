<?php
namespace iRESTful\DSLs\Domain;

interface DSL {
    public function getName();
    public function getType();
    public function getUrl();
    public function getLicense();
    public function getAuthors();
    public function getProject();
    public function getBaseDirectory();
}
