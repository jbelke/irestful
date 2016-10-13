<?php
namespace iRESTful\Instructions\Infrastructure\Objects;
use iRESTful\Instructions\Domain\Values\Value;
use iRESTful\Instructions\Domain\Values\Loops\Loop;
use iRESTful\DSLs\Domain\Projects\Values\Value as InputValue;
use iRESTful\Instructions\Domain\Values\Exceptions\ValueException;

final class ConcreteInstructionValue implements Value {
    private $loop;
    private $value;
    public function __construct(Loop $loop = null, InputValue $value = null) {

        $amount = (empty($loop) ? 0 : 1) + (empty($value) ? 0 : 1);
        if ($amount != 1) {
            throw new ValueException('There must be either a loop or a value object.  '.$amount.' given.');
        }

        $this->loop = $loop;
        $this->value = $value;
    }

    public function hasLoop() {
        return !empty($this->loop);
    }

    public function getLoop() {
        return $this->loop;
    }

    public function hasValue() {
        return !empty($this->value);
    }

    public function getValue() {
        return $this->value;
    }
}