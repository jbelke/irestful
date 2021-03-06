<?php
namespace iRESTful\Rodson\DSLs\Tests\Tests\Unit\Objects;
use iRESTful\Rodson\DSLs\Infrastructure\Objects\ConcreteDatabaseType;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Exceptions\DatabaseTypeException;

final class ConcreteDatabaseTypeTest extends \PHPUnit_Framework_TestCase {
    private $binaryMock;
    private $floatMock;
    private $integerMock;
    private $stringMock;
    public function setUp() {
        $this->binaryMock = $this->createMock('iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Binaries\BinaryType');
        $this->floatMock = $this->createMock('iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Floats\FloatType');
        $this->integerMock = $this->createMock('iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Integers\IntegerType');
        $this->stringMock = $this->createMock('iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Strings\StringType');
    }

    public function tearDown() {

    }

    public function testCreate_withBinary_Success() {

        $type = new ConcreteDatabaseType(false, $this->binaryMock);

        $this->assertFalse($type->hasBoolean());
        $this->assertTrue($type->hasBinary());
        $this->assertEquals($this->binaryMock, $type->getBinary());
        $this->assertFalse($type->hasFloat());
        $this->assertNull($type->getFloat());
        $this->assertFalse($type->hasInteger());
        $this->assertNull($type->getInteger());
        $this->assertFalse($type->hasString());
        $this->assertNull($type->getString());

    }

    public function testCreate_withFloat_Success() {

        $type = new ConcreteDatabaseType(false, null, $this->floatMock);

        $this->assertFalse($type->hasBoolean());
        $this->assertFalse($type->hasBinary());
        $this->assertNull($type->getBinary());
        $this->assertTrue($type->hasFloat());
        $this->assertEquals($this->floatMock, $type->getFloat());
        $this->assertFalse($type->hasInteger());
        $this->assertNull($type->getInteger());
        $this->assertFalse($type->hasString());
        $this->assertNull($type->getString());

    }

    public function testCreate_withInteger_Success() {

        $type = new ConcreteDatabaseType(false, null, null, $this->integerMock);

        $this->assertFalse($type->hasBoolean());
        $this->assertFalse($type->hasBinary());
        $this->assertNull($type->getBinary());
        $this->assertFalse($type->hasFloat());
        $this->assertNull($type->getFloat());
        $this->assertTrue($type->hasInteger());
        $this->assertEquals($this->integerMock, $type->getInteger());
        $this->assertFalse($type->hasString());
        $this->assertNull($type->getString());

    }

    public function testCreate_withString_Success() {

        $type = new ConcreteDatabaseType(false, null, null, null, $this->stringMock);

        $this->assertFalse($type->hasBoolean());
        $this->assertFalse($type->hasBinary());
        $this->assertNull($type->getBinary());
        $this->assertFalse($type->hasFloat());
        $this->assertNull($type->getFloat());
        $this->assertFalse($type->hasInteger());
        $this->assertNull($type->getInteger());
        $this->assertTrue($type->hasString());
        $this->assertEquals($this->stringMock, $type->getString());

    }

    public function testCreate_withBoolean_Success() {

        $type = new ConcreteDatabaseType(true);

        $this->assertTrue($type->hasBoolean());
        $this->assertFalse($type->hasBinary());
        $this->assertNull($type->getBinary());
        $this->assertFalse($type->hasFloat());
        $this->assertNull($type->getFloat());
        $this->assertFalse($type->hasInteger());
        $this->assertNull($type->getInteger());
        $this->assertFalse($type->hasString());
        $this->assertNull($type->getString());

    }

    public function testCreate_withTooManyTypes_throwsDatabaseTypeException() {

        $asserted = false;
        try {

            new ConcreteDatabaseType(true, $this->binaryMock, $this->floatMock, $this->integerMock, $this->stringMock);

        } catch (DatabaseTypeException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

    public function testCreate_withoutType_throwsDatabaseTypeException() {

        $asserted = false;
        try {

            new ConcreteDatabaseType(false);

        } catch (DatabaseTypeException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

}
