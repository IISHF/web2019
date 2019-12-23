<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Tests\Doctrine\DBAL\Types;

use App\Doctrine\DBAL\Types\UtcDateTimeType;
use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class UtcDateTimeTypeTest
 *
 * @package App\Tests\Doctrine\DBAL\Types
 */
class UtcDateTimeTypeTest extends TestCase
{
    /**
     * @return AbstractPlatform|MockObject
     */
    private function createPlatform(): AbstractPlatform
    {
        return $this->getMockForAbstractClass(
            AbstractPlatform::class,
            [],
            '',
            true,
            true,
            true,
            ['getDateTimeFormatString']
        );
    }

    public function testConvertToDatabaseValueWithNull(): void
    {
        $type     = new UtcDateTimeType();
        $platform = $this->createPlatform();
        $platform->expects(self::never())
                 ->method('getDateTimeFormatString');

        self::assertNull($type->convertToDatabaseValue(null, $platform));
    }

    public function testConvertToDatabaseValueWithDateTimeUtc(): void
    {
        $type     = new UtcDateTimeType();
        $platform = $this->createPlatform();
        $platform->method('getDateTimeFormatString')
                 ->willReturn('Y-m-d H:i:s');
        self::assertEquals(
            '2019-06-03 12:00:00',
            $type->convertToDatabaseValue(new DateTime('2019-06-03 12:00:00', new DateTimeZone('UTC')), $platform)
        );
    }

    public function testConvertToDatabaseValueWithDateTimeTimezone(): void
    {
        $type     = new UtcDateTimeType();
        $platform = $this->createPlatform();
        $platform->method('getDateTimeFormatString')
                 ->willReturn('Y-m-d H:i:s');
        self::assertEquals(
            '2019-06-03 10:00:00',
            $type->convertToDatabaseValue(
                new DateTime('2019-06-03 12:00:00', new DateTimeZone('Europe/Berlin')),
                $platform
            )
        );
    }

    public function testConvertToDatabaseValueFailure(): void
    {
        $type     = new UtcDateTimeType();
        $platform = $this->createPlatform();
        $platform->expects(self::never())
                 ->method('getDateTimeFormatString');

        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage(
            'Could not convert PHP value \'foo\' of type \'string\' to type \'datetime_utc\'. Expected one of the following types: null, DateTime'
        );
        $type->convertToDatabaseValue('foo', $platform);
    }

    public function testConvertToPHPValueWithNull(): void
    {
        $type     = new UtcDateTimeType();
        $platform = $this->createPlatform();
        $platform->expects(self::never())
                 ->method('getDateTimeFormatString');

        self::assertNull($type->convertToPHPValue(null, $platform));
    }

    public function testConvertToPHPValueWithDateTime(): void
    {
        $type     = new UtcDateTimeType();
        $platform = $this->createPlatform();
        $platform->method('getDateTimeFormatString')
                 ->willReturn('Y-m-d H:i:s');

        self::assertEquals(
            new DateTime('2019-06-03 12:00:00', new DateTimeZone('UTC')),
            $type->convertToPHPValue('2019-06-03 12:00:00', $platform)
        );
    }

    public function testConvertToPHPValueFailure(): void
    {
        $type     = new UtcDateTimeType();
        $platform = $this->createPlatform();
        $platform->method('getDateTimeFormatString')
                 ->willReturn('Y-m-d H:i:s');

        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage(
            'Could not convert database value "foo" to Doctrine Type datetime_utc. Expected format: Y-m-d H:i:s'
        );
        $type->convertToPHPValue('foo', $platform);
    }
}
