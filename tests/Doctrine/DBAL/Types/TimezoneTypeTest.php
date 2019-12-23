<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Tests\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\TimezoneType;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\TestCase;

/**
 * Class TimezoneTypeTest
 *
 * @package App\Tests\Doctrine\DBAL\Types
 */
class TimezoneTypeTest extends TestCase
{
    public function testGetSQLDeclaration(): void
    {
        $type     = new TimezoneType();
        $platform = $this->getMockForAbstractClass(
            AbstractPlatform::class,
            [],
            '',
            true,
            true,
            true,
            ['getVarcharTypeDeclarationSQL']
        );
        $platform->expects(self::once())
                 ->method('getVarcharTypeDeclarationSQL')
                 ->with(
                     [
                         'length' => 64,
                         'fixed'  => false,
                     ]
                 )
                 ->willReturn('DUMMYVARCHAR()');

        self::assertEquals('DUMMYVARCHAR()', $type->getSQLDeclaration([], $platform));
    }

    public function testConvertToDatabaseValueWithNull(): void
    {
        $type     = new TimezoneType();
        $platform = $this->getMockForAbstractClass(AbstractPlatform::class);
        self::assertNull($type->convertToDatabaseValue(null, $platform));
    }

    public function testConvertToDatabaseValueWithTimezone(): void
    {
        $type     = new TimezoneType();
        $platform = $this->getMockForAbstractClass(AbstractPlatform::class);
        self::assertEquals(
            'Europe/Berlin',
            $type->convertToDatabaseValue(new DateTimeZone('Europe/Berlin'), $platform)
        );
    }

    public function testConvertToDatabaseValueFailure(): void
    {
        $type     = new TimezoneType();
        $platform = $this->getMockForAbstractClass(AbstractPlatform::class);

        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage(
            'Could not convert PHP value \'foo\' of type \'string\' to type \'timezone\'. Expected one of the following types: null, DateTimeZone'
        );
        $type->convertToDatabaseValue('foo', $platform);
    }

    public function testConvertToPHPValueWithNull(): void
    {
        $type     = new TimezoneType();
        $platform = $this->getMockForAbstractClass(AbstractPlatform::class);
        self::assertNull($type->convertToPHPValue(null, $platform));
    }

    public function testConvertToPHPValueWithTimezone(): void
    {
        $type     = new TimezoneType();
        $platform = $this->getMockForAbstractClass(AbstractPlatform::class);
        self::assertEquals(new DateTimeZone('Europe/Berlin'), $type->convertToPHPValue('Europe/Berlin', $platform));
    }

    public function testConvertToPHPValueFailure(): void
    {
        $type     = new TimezoneType();
        $platform = $this->getMockForAbstractClass(AbstractPlatform::class);

        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Could not convert database value "foo" to Doctrine Type timezone');
        $type->convertToPHPValue('foo', $platform);
    }
}
