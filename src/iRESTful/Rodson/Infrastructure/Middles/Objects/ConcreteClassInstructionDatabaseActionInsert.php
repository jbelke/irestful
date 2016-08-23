<?php
namespace iRESTful\Rodson\Infrastructure\Middles\Objects;
use iRESTful\Rodson\Domain\Middles\Classes\Instructions\Databases\Actions\Inserts\Insert;
use iRESTful\Rodson\Domain\Middles\Classes\Instructions\Assignments\Assignment;
use iRESTful\Rodson\Domain\Middles\Classes\Instructions\Databases\Actions\Inserts\Exceptions\InsertException;

final class ConcreteClassInstructionDatabaseActionInsert implements Insert {
    private $assignment;
    private $assignments;
    public function __construct(Assignment $assignment = null, array $assignments = null) {

        if (empty($assignments)) {
            $assignments = null;
        }

        $amount = (empty($assignment) ? 0 : 1) + (empty($assignments) ? 0 : 1);
        if ($amount != 1) {
            throw new InsertException('One of these must be non-empty: assignment, assignments.  '.$amount.' given.');
        }

        $this->assignment = $assignment;
        $this->assignments = $assignments;

    }

    public function hasAssignment() {
        return !empty($this->assignment);
    }

    public function getAssignment() {
        return $this->assignment;
    }

    public function hasAssignments() {
        return !empty($this->assignments);
    }

    public function getAssignments() {
        return $this->assignments;
    }

    public function getData() {
        $output = [];
        if ($this->hasAssignment()) {
            $output['assignment'] = $this->getAssignment()->getData();
        }

        if ($this->hasAssignments()) {
            $output['assignments'] = $this->getAssignments()->getData();
        }

        return $output;
    }
}
