<?php
namespace iRESTful\Rodson\DSLs\Tests\Tests\Unit\Objects;
use iRESTful\Rodson\DSLs\Infrastructure\Objects\ConcreteDatabaseTypeInteger;
use iRESTful\Rodson\DSLs\Domain\Projects\Types\Databases\Integers\Exceptions\IntegerException;

final class ConcreteDatabaseTypeIntegerTest extends \PHPUnit_Framework_TestCase {
    private $maximumBitSize;
    public function setUp() {
        $this->maximumBitSize = rand(1, 500);
    }

    public function tearDown() {

    }

    public function testCreate_Success() {

        $integer = new ConcreteDatabaseTypeInteger($this->maximumBitSize);

        $this->assertEquals($this->maximumBitSize, $integer->getMaximumBitSize());

    }

    public function testCreate_withZeroMaximumBitSize_throwsIntegerException() {

        $asserted = false;
        try {

            new ConcreteDatabaseTypeInteger(0);

        } catch (IntegerException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

    public function testCreate_withNegativeMaximumBitSize_throwsIntegerException() {

        $asserted = false;
        try {

            new ConcreteDatabaseTypeInteger($this->maximumBitSize * -1);

        } catch (IntegerException $exception) {
            $asserted = true;
        }

        $this->assertTrue($asserted);

    }

}
