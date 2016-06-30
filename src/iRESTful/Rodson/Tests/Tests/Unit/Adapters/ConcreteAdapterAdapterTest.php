<?php
namespace iRESTful\Rodson\Tests\Tests\Unit\Adapters;
use iRESTful\Rodson\Infrastructure\Adapters\ConcreteAdapterAdapter;
use iRESTful\Rodson\Tests\Helpers\Adapters\MethodAdapterHelper;
use iRESTful\Rodson\Domain\Adapters\Exceptions\AdapterException;

final class ConcreteAdapterAdapterTest extends \PHPUnit_Framework_TestCase {
    private $methodAdapterMock;
    private $methodMock;
    private $typeMock;
    private $methodName;
    private $types;
    private $data;
    private $adapter;
    private $methodAdapterHelper;
    public function setUp() {
        $this->methodAdapterMock = $this->getMock('iRESTful\Rodson\Domain\Codes\Methods\Adapters\MethodAdapter');
        $this->methodMock = $this->getMock('iRESTful\Rodson\Domain\Codes\Methods\Method');
        $this->typeMock = $this->getMock('iRESTful\Rodson\Domain\Types\Type');

        $this->methodName = 'myMethod';

        $this->types = [
            'string' => $this->typeMock,
            'base_url' => $this->typeMock
        ];

        $this->data = [
            'from' => 'string',
            'to' => 'base_url',
            'method' => $this->methodName
        ];

        $this->adapter = new ConcreteAdapterAdapter($this->methodAdapterMock, $this->types);

        $this->methodAdapterHelper = new MethodAdapterHelper($this, $this->methodAdapterMock);
    }

    public function tearDown() {

    }

    public function testFromDataToAdapter_Success() {

        $this->methodAdapterHelper->expectsFromStringToMethod_Success($this->methodMock, $this->methodName);

        $adapter = $this->adapter->fromDataToAdapter($this->data);

        $this->assertEquals($this->typeMock, $adapter->fromType());
        $this->assertEquals($this->typeMock, $adapter->toType());
        $this->assertEquals($this->methodMock, $adapter->getMethod());

    }

    public function testFromDataToAdapter_throwsMethodException_throwsAdapterException() {

        $this->methodAdapterHelper->expectsFromStringToMethod_throwsMethodException($this->methodName);

        $asserted = false;
        try {

            $this->adapter->fromDataToAdapter($this->data);

        } catch (AdapterException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

    public function testFromDataToAdapter_withFromNameNotFoundInTypes_throwsAdapterException() {

        $this->data['from'] = 'invalid_from';

        $asserted = false;
        try {

            $this->adapter->fromDataToAdapter($this->data);

        } catch (AdapterException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

    public function testFromDataToAdapter_withToNameNotFoundInTypes_throwsAdapterException() {

        $this->data['to'] = 'invalid_to';

        $asserted = false;
        try {

            $this->adapter->fromDataToAdapter($this->data);

        } catch (AdapterException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

    public function testFromDataToAdapter_withoutMethod_throwsAdapterException() {

        unset($this->data['method']);

        $asserted = false;
        try {

            $this->adapter->fromDataToAdapter($this->data);

        } catch (AdapterException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

    public function testFromDataToAdapter_withoutFrom_throwsAdapterException() {

        unset($this->data['from']);

        $asserted = false;
        try {

            $this->adapter->fromDataToAdapter($this->data);

        } catch (AdapterException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

    public function testFromDataToAdapter_withoutTo_throwsAdapterException() {

        unset($this->data['to']);

        $asserted = false;
        try {

            $this->adapter->fromDataToAdapter($this->data);

        } catch (AdapterException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

    public function testFromDataToAdapters_Success() {

        $this->methodAdapterHelper->expectsFromStringToMethod_Success($this->methodMock, $this->methodName);

        $adapters = $this->adapter->fromDataToAdapters([$this->data]);

        $this->assertEquals($this->typeMock, $adapters[0]->fromType());
        $this->assertEquals($this->typeMock, $adapters[0]->toType());
        $this->assertEquals($this->methodMock, $adapters[0]->getMethod());

    }

}
