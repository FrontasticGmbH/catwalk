<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;

class ObjectTypeTest extends TestCase
{
    private ObjectType $type;

    private AbstractPlatform $platform;

    protected function setUp(): void
    {
        $this->type = new ObjectType();
        $this->platform = new MySQLPlatform();
    }

    public function testDeclaresClobColumn()
    {
        $this->assertSame('LONGTEXT', $this->type->getSQLDeclaration([], $this->platform));
    }

    public function testStoresSerializedValue()
    {
        $value = new \stdClass();
        $value->name = 'Frontastic';
        $value->tags = ['a', 'b'];

        $this->assertSame(
            serialize($value),
            $this->type->convertToDatabaseValue($value, $this->platform)
        );
    }

    public function testRoundTripsObjectsAndArrays()
    {
        $object = new \stdClass();
        $object->nested = ['key' => 42, 'list' => [1.5, true, null]];

        foreach ([$object, ['plain' => 'array'], 'string', 7] as $value) {
            $stored = $this->type->convertToDatabaseValue($value, $this->platform);
            $this->assertEquals($value, $this->type->convertToPHPValue($stored, $this->platform));
        }
    }

    public function testNullStaysNull()
    {
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));
    }

    public function testReadsFromStreamResource()
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, serialize(['from' => 'stream']));
        rewind($stream);

        $this->assertSame(['from' => 'stream'], $this->type->convertToPHPValue($stream, $this->platform));
    }

    public function testInvalidPayloadThrowsConversionException()
    {
        $this->expectException(ConversionException::class);

        $this->type->convertToPHPValue('this is not serialized data', $this->platform);
    }

    public function testInvalidPayloadRestoresErrorHandler()
    {
        $handler = function () {
            return true;
        };
        set_error_handler($handler);

        try {
            $this->type->convertToPHPValue('this is not serialized data', $this->platform);
        } catch (ConversionException $e) {
            // expected
        }

        $this->assertSame($handler, set_error_handler(null));
        restore_error_handler();
        restore_error_handler();
    }

    public function testIsRegisteredUnderTheObjectName()
    {
        $this->assertSame('object', $this->type->getName());
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
