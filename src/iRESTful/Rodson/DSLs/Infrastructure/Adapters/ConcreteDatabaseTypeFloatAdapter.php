<?php
namespace iRESTful\Rodson\DSLs\Infrastructure\Adapters;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Floats\Adapters\FloatAdapter;
use iRESTful\Rodson\DSLs\Infrastructure\Objects\ConcreteDatabaseTypeFloat;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Floats\Exceptions\FloatException;

final class ConcreteDatabaseTypeFloatAdapter implements FloatAdapter {

    public function __construct() {

    }

    public function fromDataToFloat(array $data) {

        if (!isset($data['digits'])) {
            throw new FloatException('The digits keyname is mandatory in order to convert data to a Float object.');
        }

        if (!isset($data['precision'])) {
            throw new FloatException('The precision keyname is mandatory in order to convert data to a Float object.');
        }

        $digits = (int) $data['digits'];
        $precision = (int) $data['precision'];
        return new ConcreteDatabaseTypeFloat($digits, $precision);

    }

}
