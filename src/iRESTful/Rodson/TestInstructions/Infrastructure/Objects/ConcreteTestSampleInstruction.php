<?php
namespace iRESTful\Rodson\TestInstructions\Infrastructure\Objects;
use iRESTful\Rodson\TestInstructions\Domain\Containers\Samples\TestSampleInstruction;
use iRESTful\Rodson\Classes\Domain\Samples\Sample;
use iRESTful\Rodson\Instructions\Domain\Instruction;
use iRESTful\Rodson\TestInstructions\Domain\Containers\Samples\Exceptions\TestSampleInstructionException;
use iRESTful\Rodson\TestInstructions\Domain\Comparisons\TestInstructionComparison;

final class ConcreteTestSampleInstruction implements TestSampleInstruction {
    private $instructions;
    private $comparison;
    public function __construct(array $instructions = null, TestInstructionComparison $comparison = null) {

        if (empty($instructions)) {
            $instructions = null;
        }

        if (!empty($instructions)) {
            foreach($instructions as $oneInstruction) {
                if (!($oneInstruction instanceof Instruction)) {
                    throw new TestSampleInstructionException('The instructions array must only contain Instruction objects.');
                }
            }
        }

        $this->instructions = $instructions;
        $this->comparison = $comparison;
    }

    public function hasInstructions() {
        return !empty($this->instructions);
    }

    public function getInstructions() {
        return $this->instructions;
    }

    public function hasComparison() {
        return !empty($this->comparison);
    }

    public function getComparison() {
        return $this->comparison;
    }

}
