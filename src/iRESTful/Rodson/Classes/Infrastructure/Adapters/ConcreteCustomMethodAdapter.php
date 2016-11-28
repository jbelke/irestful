<?php
namespace iRESTful\Rodson\Classes\Infrastructure\Adapters;
use iRESTful\Rodson\DSLs\Domain\Projects\Objects\Methods\Method;
use iRESTful\Rodson\Classes\Domain\CustomMethods\Adapters\CustomMethodAdapter;
use iRESTful\Rodson\Classes\Domain\CustomMethods\Exceptions\CustomMethodException;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Type;
use iRESTful\Rodson\DSLs\Domain\Projects\Objects\Object;
use iRESTful\Rodson\Classes\Domain\Interfaces\Methods\Parameters\Adapters\ParameterAdapter;
use iRESTful\Rodson\Classes\Infrastructure\Objects\ConcreteCustomMethod;
use iRESTful\Rodson\DSLs\Domain\Projects\Codes\Methods\Method as CodeMethod;
use iRESTful\Rodson\DSLs\Domain\Projects\Converters\Converter;
use iRESTful\Rodson\Classes\Infrastructure\Objects\ConcreteNamespace;
use iRESTful\Rodson\Instructions\Domain\Instruction;
use iRESTful\Rodson\Instructions\Domain\Databases\Actions\Action;
use iRESTful\Rodson\DSLs\Domain\Projects\Controllers\HttpRequests\HttpRequest;
use iRESTful\Rodson\DSLs\Domain\Projects\Controllers\HttpRequests\Commands\Urls\Url;
use iRESTful\Rodson\DSLs\Domain\Projects\Controllers\HttpRequests\Commands\Actions\Action as HttpRequestAction;
use iRESTful\Rodson\Instructions\Domain\Databases\Actions\Inserts\Insert;
use iRESTful\Rodson\Instructions\Domain\Assignments\Assignment;
use iRESTful\Rodson\Instructions\Domain\Conversions\Conversion;
use iRESTful\Rodson\Instructions\Domain\Databases\Actions\Deletes\Delete;
use iRESTful\Rodson\Instructions\Domain\Databases\Database;
use iRESTful\Rodson\Instructions\Domain\Databases\Actions\Updates\Update;
use iRESTful\Rodson\Instructions\Domain\Databases\Retrievals\Retrieval;
use iRESTful\Rodson\Instructions\Domain\Databases\Retrievals\Entities\Entity;
use iRESTful\Rodson\DSLs\Domain\Projects\Values\Value;
use iRESTful\Rodson\Instructions\Domain\Databases\Retrievals\Multiples\MultipleEntity;
use iRESTful\Rodson\ClassesEntities\Domain\Annotations\AnnotatedEntity;
use iRESTful\Rodson\Instructions\Domain\Databases\Retrievals\EntityPartialSets\EntityPartialSet;
use iRESTful\Rodson\Instructions\Domain\Containers\Container;
use iRESTful\Rodson\DSLs\Domain\Projects\Converters\Types\Type as ConverterType;
use iRESTful\Rodson\Instructions\Domain\Tests\Comparisons\TestInstructionComparison;
use iRESTful\Rodson\Instructions\Domain\Tests\Containers\TestContainerInstruction;
use iRESTful\Rodson\Instructions\Domain\Values\Value as InstructionValue;
use iRESTful\Rodson\TestInstructions\Domain\TestInstruction;
use iRESTful\Rodson\Classes\Domain\CustomMethods\SourceCodes\Adapters\SourceCodeAdapter;
use iRESTful\Rodson\DSLs\Domain\Projects\Primitives\Adapters\PrimitiveAdapter;

final class ConcreteCustomMethodAdapter implements CustomMethodAdapter {
    private $primitiveAdapter;
    private $parameterAdapter;
    private $sourceCodeAdapter;
    public function __construct(PrimitiveAdapter $primitiveAdapter, ParameterAdapter $parameterAdapter, SourceCodeAdapter $sourceCodeAdapter) {
        $this->primitiveAdapter = $primitiveAdapter;
        $this->parameterAdapter = $parameterAdapter;
        $this->sourceCodeAdapter = $sourceCodeAdapter;
    }

    public function fromDataToCustomMethod(array $data) {

        if (!isset($data['instructions'])) {
            //throws
        }

        if (!isset($data['method_name'])) {
            //throws
        }

        $sourceCode = $this->sourceCodeAdapter->fromDataToSourceCode($data);
        return new ConcreteCustomMethod($data['method_name'], $sourceCode);

    }

    public function fromControllerInstructionsToCustomMethod(array $instructions) {
        $name = 'execute';
        $sourceCode = $this->sourceCodeAdapter->fromInstructionsToControllerSourceCode($instructions);
        $parameter = $this->parameterAdapter->fromDataToParameter([
            'name' => 'httpRequest',
            'namespace' => new ConcreteNamespace(explode('\\', 'iRESTful\LeoPaul\Objects\Libraries\Https\Domain\Requests\HttpRequest'))
        ]);

        return new ConcreteCustomMethod($name, $sourceCode, [$parameter]);
    }

    public function fromObjectToCustomMethods(Object $object) {

        if (!$object->hasMethods()) {
            return [];
        }

        $methods = $object->getMethods();
        return $this->fromMethodsToCustomMethods($methods);

    }

    public function fromMethodsToCustomMethods(array $methods) {
        $output = [];
        foreach($methods as $oneMethod) {
            $output[] = $this->fromMethodToCustomMethod($oneMethod);
        }

        return $output;
    }

    public function fromMethodToCustomMethod(Method $method) {
        $name = $method->getName();
        $codeMethod = $method->getMethod();
        return $this->createClassMethodCustom($name, $codeMethod);
    }

    public function fromTypeToCustomMethod(Type $type) {

        if (!$type->hasMethod()) {
            return null;
        }

        $method = $type->getMethod();
        return $this->createClassMethodCustom('validate', $method);
    }

    private function createClassMethodCustom($name, CodeMethod $codeMethod) {

        $removeBraces = function(array $code) {

            $fixIndentation = function(array $lines) {
                $output = [];
                $rightSize = 0;
                $size = null;
                $amountToAdd = null;
                $amountToSubstract = null;
                foreach($lines as $index => $oneLine) {

                    if (is_null($size)) {
                        $size = strlen($oneLine) - strlen(ltrim($oneLine));

                        if ($size < $rightSize) {
                            $amountToAdd = $rightSize - $size;
                        }

                        if ($size > $rightSize) {
                            $amountToSubstract = $size - $rightSize;
                        }
                    }

                    $filtered = '';
                    if (!is_null($amountToAdd)) {
                        $filtered = str_repeat(' ', $amountToAdd).$oneLine;
                    }

                    if (!is_null($amountToSubstract)) {
                        $filtered = substr($oneLine, $amountToSubstract);
                    }

                    if (!empty($filtered)) {
                        $output[$index] = $filtered;
                    }

                }

                return $output;
            };

            $codeWithBraces = implode(PHP_EOL, $code);
            $firstPos = strpos($codeWithBraces, '{');
            if ($firstPos === 0) {
                $codeWithBraces = substr($codeWithBraces, 1);
            }

            $lastPos = strrpos($codeWithBraces, '}');
            $length = strlen($codeWithBraces) - 1;
            if ($lastPos === $length) {
                $codeWithBraces = substr($codeWithBraces, 0, $length - 2);
            }

            $lines = explode(PHP_EOL, $codeWithBraces);
            return $fixIndentation(array_values(array_filter($lines)));

        };

        $getSourceCodeLines = function(\ReflectionMethod $reflectionMethod) use(&$removeBraces) {

            $fileName = $reflectionMethod->getFileName();
            $startLine = $reflectionMethod->getStartLine();
            $endLine = $reflectionMethod->getEndLine();
            $numLines = $endLine - $startLine;

            $contents = file_get_contents($fileName);
            $contentLines = explode(PHP_EOL, $contents);
            $sliced = array_slice($contentLines, $startLine, $numLines);
            return $removeBraces($sliced);
        };

        $parameterAdapter = $this->parameterAdapter;
        $getParameters = function(\ReflectionMethod $reflectionMethod) use(&$parameterAdapter) {
            $parameters = [];
            $reflectionParameters = $reflectionMethod->getParameters();
            foreach($reflectionParameters as $oneReflectionParameter) {

                $isOptional = false;
                if ($oneReflectionParameter->isOptional()) {
                    $isOptional = true;
                }

                $isArray = false;
                if ($oneReflectionParameter->isArray()) {
                    $isArray = true;
                }

                $primitive = null;
                if (!$isArray && $oneReflectionParameter->hasType()) {
                    $oneReflectionType = $oneReflectionParameter->getType();
                    $primitive = $this->primitiveAdapter->fromNameToPrimitive($oneReflectionType);
                }

                $parameters[] = $parameterAdapter->fromDataToParameter([
                    'name' => $oneReflectionParameter->getName(),
                    'is_optional' => $isOptional,
                    'is_array' => $isArray,
                    'primitive' => $primitive
                ]);
            }

            return $parameters;
        };

        $code = $codeMethod->getCode();
        $methodName = $codeMethod->getMethodName();
        $className = $code->getClassName();
        $reflectionMethod = new \ReflectionMethod($className, $methodName);

        $language = $code->getLanguage()->get();
        if ($language != 'PHP') {
            throw new CustomMethodException('The input language ('.$language.') is not yet supported.');
        }

        $sourceCodeLines = $getSourceCodeLines($reflectionMethod);
        $sourceCode = $this->sourceCodeAdapter->fromSourceCodeLinesToSourceCode($sourceCodeLines);
        $parameters = $getParameters($reflectionMethod);
        return new ConcreteCustomMethod($name, $sourceCode, $parameters);
    }

}
