<?php
namespace iRESTful\Rodson\Domain\Inputs\Controllers\HttpRequests\Commands\Actions;

interface Action {
    public function isRetrieval();
    public function isInsert();
    public function isUpdate();
    public function isDelete();
    public function getData();
}
