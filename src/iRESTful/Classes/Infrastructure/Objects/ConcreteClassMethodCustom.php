<?php
namespace iRESTful\Classes\Infrastructure\Objects;
use iRESTful\Classes\Domain\Methods\Customs\CustomMethod;
use iRESTful\Classes\Domain\Methods\Customs\Exceptions\CustomMethodException;
use iRESTful\Classes\Domain\Interfaces\Methods\Parameters\Parameter;

final class ConcreteClassMethodCustom implements CustomMethod {
    private $name;
    private $sourceCodeLines;
    private $parameters;
    public function __construct($name, array $sourceCodeLines = null, array $parameters = null) {

        if (empty($sourceCodeLines)) {
            $sourceCodeLines = null;
        }

        if (empty($name) || !is_string($name)) {
            throw new CustomMethodException('The name must be a non-empty string.');
        }

        if (!empty($sourceCodeLines)) {
            foreach($sourceCodeLines as $oneSourceCodeLine) {
                if (!empty($oneSourceCodeLine) && !is_string($oneSourceCodeLine)) {
                    throw new CustomMethodException('The sourceCodeLines array must only contain string lines.');
                }
            }
        }

        if (!empty($parameters)) {
            foreach($parameters as $oneParameter) {
                if (!($oneParameter instanceof Parameter)) {
                    throw new CustomMethodException('The parameters array must only contain Parameter objects.');
                }
            }
        }

        $this->name = $name;
        $this->sourceCodeLines = $sourceCodeLines;
        $this->parameters = $parameters;

    }

    public function getName() {
        return $this->name;
    }

    public function hasSourceCodeLines() {
        return !empty($this->sourceCodeLines);
    }

    public function getSourceCodeLines() {
        return $this->sourceCodeLines;
    }

    public function hasParameters() {
        return !empty($this->parameters);
    }

    public function getParameters() {
        return $this->parameters;
    }

}