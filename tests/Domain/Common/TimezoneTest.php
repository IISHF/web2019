<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Tests\Domain\Common;

use App\Domain\Common\Timezone;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class TimezoneTest
 *
 * @package App\Tests\Domain\Common
 */
class TimezoneTest extends TestCase
{
    public function testGetUtcReturnsSameInstance(): void
    {
        $utc = Timezone::getUtc();
        self::assertEquals('UTC', $utc->getName());
        self::assertSame($utc, Timezone::getUtc());
    }

    public function testGetWithNullReturnsUtc(): void
    {
        self::assertSame(Timezone::getUtc(), Timezone::get(null));
    }

    public function testGetWithTimezoneReturnsSameInstance(): void
    {
        $tz = new DateTimeZone('Europe/Berlin');
        self::assertSame($tz, Timezone::get($tz));
    }

    public function testGetWithTimezoneStringReturnsSameInstance(): void
    {
        $tz = Timezone::get('Europe/Berlin');
        self::assertEquals('Europe/Berlin', $tz->getName());
        self::assertSame($tz, Timezone::get('Europe/Berlin'));
    }

    public function testGetFailsWithInvalidTimezone(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(' Unknown or bad timezone (DoesNotExist)');
        Timezone::get('DoesNotExist');
    }

    public function testEnsureTimezoneReturnsSameInstanceIfSameTimezone(): void
    {
        $d1 = new DateTimeImmutable('2019-06-03 12:00:00', new DateTimeZone('Europe/Berlin'));
        $d2 = Timezone::ensureTimezone($d1, new DateTimeZone('Europe/Berlin'));
        self::assertSame($d1, $d2);
    }

    public function testEnsureTimezoneChangesTimezoneButDoesNotAdjustDate(): void
    {
        $d1 = new DateTimeImmutable('2019-06-03 12:00:00', new DateTimeZone('Europe/Berlin'));
        $d2 = Timezone::ensureTimezone($d1, new DateTimeZone('America/New_York'));
        self::assertNotSame($d1, $d2);
        self::assertEquals('Europe/Berlin', $d1->getTimezone()->getName());
        self::assertEquals('America/New_York', $d2->getTimezone()->getName());
        self::assertEquals('2019-06-03T12:00:00+02:00', $d1->format(DateTime::ATOM));
        self::assertEquals('2019-06-03T12:00:00-04:00', $d2->format(DateTime::ATOM));
    }

    public function testEnsureUtcReturnsSameInstanceIfIsUtc(): void
    {
        $d1 = new DateTimeImmutable('2019-06-03 12:00:00', new DateTimeZone('UTC'));
        $d2 = Timezone::ensureUtc($d1);
        self::assertSame($d1, $d2);
    }

    public function testEnsureUtcChangesTimezoneButDoesNotAdjustDate(): void
    {
        $d1 = new DateTimeImmutable('2019-06-03 12:00:00', new DateTimeZone('Europe/Berlin'));
        $d2 = Timezone::ensureUtc($d1);
        self::assertNotSame($d1, $d2);
        self::assertEquals('Europe/Berlin', $d1->getTimezone()->getName());
        self::assertEquals('UTC', $d2->getTimezone()->getName());
        self::assertEquals('2019-06-03T12:00:00+02:00', $d1->format(DateTime::ATOM));
        self::assertEquals('2019-06-03T12:00:00+00:00', $d2->format(DateTime::ATOM));
    }

    public function testConvertToTimezoneReturnsSameInstanceIfSameTimezone(): void
    {
        $d1 = new DateTimeImmutable('2019-06-03 12:00:00', new DateTimeZone('Europe/Berlin'));
        $d2 = Timezone::convertToTimezone($d1, new DateTimeZone('Europe/Berlin'));
        self::assertSame($d1, $d2);
    }

    public function testConvertToTimezoneChangesTimezone(): void
    {
        $d1 = new DateTimeImmutable('2019-06-03 12:00:00', new DateTimeZone('Europe/Berlin'));
        $d2 = Timezone::convertToTimezone($d1, new DateTimeZone('America/New_York'));
        self::assertNotSame($d1, $d2);
        self::assertEquals('Europe/Berlin', $d1->getTimezone()->getName());
        self::assertEquals('America/New_York', $d2->getTimezone()->getName());
        self::assertEquals('2019-06-03T12:00:00+02:00', $d1->format(DateTime::ATOM));
        self::assertEquals('2019-06-03T06:00:00-04:00', $d2->format(DateTime::ATOM));
    }

    public function testConvertToUtcReturnsSameInstanceIfIsUtc(): void
    {
        $d1 = new DateTimeImmutable('2019-06-03 12:00:00', new DateTimeZone('UTC'));
        $d2 = Timezone::convertToUtc($d1);
        self::assertSame($d1, $d2);
    }

    public function testConvertToUtcChangesTimezoneToUtc(): void
    {
        $d1 = new DateTimeImmutable('2019-06-03 12:00:00', new DateTimeZone('Europe/Berlin'));
        $d2 = Timezone::convertToUtc($d1);
        self::assertNotSame($d1, $d2);
        self::assertEquals('Europe/Berlin', $d1->getTimezone()->getName());
        self::assertEquals('UTC', $d2->getTimezone()->getName());
        self::assertEquals('2019-06-03T12:00:00+02:00', $d1->format(DateTime::ATOM));
        self::assertEquals('2019-06-03T10:00:00+00:00', $d2->format(DateTime::ATOM));
    }

    /**
     * @dataProvider timezoneNameProvider
     *
     * @param string $timezone
     * @param string $expected
     */
    public function testGetName(string $timezone, string $expected): void
    {
        $tz = new DateTimeZone($timezone);
        self::assertEquals($expected, Timezone::getName($tz));
    }

    public function timezoneNameProvider(): array
    {
        return [
            ['Europe/Berlin', 'Europe/Berlin'],
            ['America/New_York', 'America/New York'],
            ['Eire', 'Other/Eire'],
            ['Etc/Greenwich', 'Etc/Greenwich'],
            ['US/Pacific', 'US/Pacific'],
            ['Poland', 'Other/Poland'],
            ['America/Argentina/San_Juan', 'America/Argentina - San Juan'],
            ['Asia/Kolkata', 'Asia/Kolkata'],
            ['Asia/Calcutta', 'Asia/Calcutta'],
        ];
    }
}
