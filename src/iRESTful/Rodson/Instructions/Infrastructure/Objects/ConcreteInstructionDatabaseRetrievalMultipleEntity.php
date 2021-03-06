<?php
namespace iRESTful\Rodson\Instructions\Infrastructure\Objects;
use iRESTful\Rodson\Instructions\Domain\Databases\Retrievals\Multiples\MultipleEntity;
use iRESTful\Rodson\Instructions\Domain\Values\Value;
use iRESTful\Rodson\Instructions\Domain\Databases\Retrievals\Keynames\Keyname;
use iRESTful\Rodson\Instructions\Domain\Containers\Container;
use iRESTful\Rodson\Instructions\Domain\Databases\Retrievals\Multiples\Exceptions\MultipleEntityException;

final class ConcreteInstructionDatabaseRetrievalMultipleEntity implements MultipleEntity {
    private $container;
    private $uuidValue;
    private $keyname;
    public function __construct(Container $container, Value $uuidValue = null, Keyname $keyname = null) {

        $amount = (empty($uuidValue) ? 0 : 1) + (empty($keyname) ? 0 : 1);
        if ($amount != 1) {
            throw new MultipleEntityException('One of these must be non-empty: uuidValue, keyname.  '.$amount.' given.');
        }

        $this->container = $container;
        $this->uuidValue = $uuidValue;
        $this->keyname = $keyname;

    }

    public function getContainer() {
        return $this->container;
    }

    public function hasUuidValue() {
        return !empty($this->uuidValue);
    }

    public function getUuidValue() {
        return $this->uuidValue;
    }

    public function hasKeyname() {
        return !empty($this->keyname);
    }

    public function getKeyname() {
        return $this->keyname;
    }

    public function getData() {
        $output = [
            'class' => $this->getClass()->getData()
        ];

        if ($this->hasUuidValue()) {
            $output['uuid'] = $this->getUuidValue()->getData();
        }

        if ($this->hasKeyname()) {
            $output['keyname'] = $this->getKeyname()->getData();
        }

        return $output;
    }

}
