<?php
namespace iRESTful\Rodson\Instructions\Infrastructure\Objects;
use iRESTful\Rodson\Instructions\Domain\Assignments\Assignment;
use iRESTful\Rodson\Instructions\Domain\Databases\Database;
use iRESTful\Rodson\Instructions\Domain\Conversions\Conversion;
use iRESTful\Rodson\Instructions\Domain\Assignments\Exeptions\AssignmentException;

final class ConcreteInstructionAssignment implements Assignment {
    private $variableName;
    private $database;
    private $conversion;
    private $mergedAssignments;
    public function __construct($variableName, Database $database = null, Conversion $conversion = null, array $mergedAssignments = null) {

        if (empty($mergedAssignments)) {
            $mergedAssignments = null;
        }

        if (empty($variableName) || !is_string($variableName)) {
            throw new AssignmentException('The variableName must be a non-empty string.');
        }

        $amount = (empty($mergedAssignments) ? 0 : 1) + (empty($database) ? 0 : 1) + (empty($conversion) ? 0 : 1);
        if ($amount != 1) {
            throw new AssignmentException('At least one of these must be non-empty: mergedAssignments, database, conversion.  '.$amount.' given.');
        }

        if (!empty($mergedAssignments)) {
            foreach($mergedAssignments as $oneMergedAssignment) {
                if (!($oneMergedAssignment instanceof Assignment)) {
                    throw new AssignmentException('The mergedAssignments array must only contain Assignment objects.');
                }
            }
        }

        $this->variableName = $variableName;
        $this->database = $database;
        $this->conversion = $conversion;
        $this->mergedAssignments = $mergedAssignments;
    }

    public function getVariableName() {
        return $this->variableName;
    }

    public function hasDatabase() {
        return !empty($this->database);
    }

    public function getDatabase() {
        return $this->database;
    }

    public function hasConversion() {
        return !empty($this->conversion);
    }

    public function getConversion() {
        return $this->conversion;
    }

    public function hasMergedAssignments() {
        return !empty($this->mergedAssignments);
    }

    public function getMergedAssignments() {
        return $this->mergedAssignments;
    }

    public function isMultipleEntities() {
        if ($this->hasDatabase()) {
            $retrieval = $this->getDatabase()->getRetrieval();
            if ($retrieval->hasMultipleEntities()) {
                return true;
            }

            return $retrieval->hasRelatedEntity();
        }

        if ($this->hasMergedAssignments()) {
            return true;
        }

        $conversion = $this->getConversion();
        if ($conversion->hasTo()) {
            $to = $conversion->getTo();
            if ($to->isMultiple()) {
                return true;
            }
        }

        if ($conversion->hasFrom()) {
            $from = $conversion->getFrom();
            if (!$from->hasAssignment()) {
                return false;
            }
        }

        return $from->getAssignment()->isMultipleEntities();
    }

    public function isPartialEntitySet() {
        if ($this->hasDatabase()) {
            return $this->getDatabase()->getRetrieval()->hasEntityPartialSet();
        }

        if ($this->hasMergedAssignments()) {
            return false;
        }

        $conversion = $this->getConversion();
        if ($conversion->hasFrom()) {
            $from = $this->getConversion()->getFrom();
            if (!$from->hasAssignment()) {
                return false;
            }
        }

        return $from->getAssignment()->isPartialEntitySet();
    }

}
