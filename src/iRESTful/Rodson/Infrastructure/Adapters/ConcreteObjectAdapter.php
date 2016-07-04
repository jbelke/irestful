<?php
namespace iRESTful\Rodson\Infrastructure\Adapters;
use iRESTful\Rodson\Domain\Objects\Properties\Adapters\PropertyAdapter;
use iRESTful\Rodson\Infrastructure\Objects\ConcreteObject;
use iRESTful\Rodson\Domain\Objects\Exceptions\ObjectException;
use iRESTful\Rodson\Domain\Objects\Properties\Exceptions\PropertyException;
use iRESTful\Rodson\Domain\Objects\Adapters\ObjectAdapter;

final class ConcreteObjectAdapter implements ObjectAdapter {
    private $propertyAdapter;
    private $databases;
    public function __construct(PropertyAdapter $propertyAdapter, array $databases) {
        $this->propertyAdapter = $propertyAdapter;
        $this->databases = $databases;
    }

    public function fromDataToObjects(array $data) {

        $output = [];
        foreach($data as $name => $oneData) {
            $oneData['name'] = $name;
            $output[] = $this->fromDataToObject($oneData);
        }

        return $output;
    }

    public function fromDataToObject(array $data) {

        if (!isset($data['name'])) {
            throw new ObjectException('The name keyname is mandatory in order to convert data to an Object object.');
        }

        if (!isset($data['properties'])) {
            throw new ObjectException('The properties keyname is mandatory in order to convert data to Property objects.');
        }

        try {

            $database = null;
            if (isset($data['database'])) {

                if (!isset($this->databases[$data['database']])) {
                    throw new ObjectException('The referenced database ('.$data['database'].') does not exists.');
                }

                $database = $this->databases[$data['database']];
            }

            $properties = $this->propertyAdapter->fromDataToProperties($data['properties']);
            return new ConcreteObject($data['name'], $properties, $database);

        } catch (PropertyException $exception) {
            throw new ObjectException('There was an exception while converting data to Property objects.', $exception);
        }
    }

}
