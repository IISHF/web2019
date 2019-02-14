<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 08:37
 */

namespace App\Domain\Common;

/**
 * Class DateTime
 *
 * @package App\Domain\Common
 */
final class DateTime
{

    /**
     * @var \DateTimeZone|null
     */
    private static $utc;

    /**
     */
    private function __construct()
    {
        // static class - left empty
    }

    /**
     * @return \DateTimeZone
     */
    public static function utc(): \DateTimeZone
    {
        if (!self::$utc) {
            self::$utc = new \DateTimeZone('UTC');
        }
        return self::$utc;
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @return \DateTimeImmutable
     */
    public static function toUtc(\DateTimeImmutable $dateTime): \DateTimeImmutable
    {
        return self::toTimeZone($dateTime, self::utc());
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @param \DateTimeZone      $toTimeZone
     * @return \DateTimeImmutable
     */
    public static function toTimeZone(\DateTimeImmutable $dateTime, \DateTimeZone $toTimeZone): \DateTimeImmutable
    {
        return $dateTime->setTimezone($toTimeZone);
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param \DateTimeZone      $inTimeZone
     * @return \DateTimeImmutable
     */
    public static function reinterpret(\DateTimeInterface $dateTime, \DateTimeZone $inTimeZone): \DateTimeImmutable
    {
        return new \DateTimeImmutable($dateTime->format('Y-m-d H:i:s.u'), $inTimeZone);
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return \DateTimeImmutable
     */
    public static function reinterpretAsUtc(\DateTimeInterface $dateTime): \DateTimeImmutable
    {
        return new \DateTimeImmutable($dateTime->format('Y-m-d H:i:s.u'), self::utc());
    }

    /**
     * @see \Symfony\Component\Form\Extension\Core\Type\TimezoneType::getTimezones()
     *
     * @param \DateTimeZone $timeZone
     * @return string
     */
    public static function formatTimeZoneName(\DateTimeZone $timeZone): string
    {
        [$region, $name] = self::getTimeZoneParts($timeZone);
        return $region . '/' . str_replace('_', ' ', $name);
    }

    /**
     * @see \Symfony\Component\Form\Extension\Core\Type\TimezoneType::getTimezones()
     *
     * @param \DateTimeZone $timeZone
     * @return string[]
     */
    public static function getTimeZoneParts(\DateTimeZone $timeZone): array
    {
        $parts = explode('/', $timeZone->getName());
        if (\count($parts) > 2) {
            $region = $parts[0];
            $name   = $parts[1] . ' - ' . $parts[2];
        } elseif (\count($parts) > 1) {
            [$region, $name] = $parts;
        } else {
            $region = 'Other';
            $name   = $parts[0];
        }

        return [$region, str_replace('_', ' ', $name)];
    }

    /**
     * @param \DateTimeZone|\DateTimeInterface $a
     * @param \DateTimeZone|\DateTimeInterface $b
     * @return bool
     */
    public static function isTimeZoneEqual($a, $b): bool
    {
        if ($a instanceof \DateTimeInterface) {
            $a = $a->getTimezone();
        }
        if ($b instanceof \DateTimeInterface) {
            $b = $b->getTimezone();
        }
        if (!$a instanceof \DateTimeZone || !$b instanceof \DateTimeZone) {
            throw new \InvalidArgumentException(
                'Parameters must either be instances of ' . \DateTimeZone::class . ' or ' . \DateTimeInterface::class . '.'
            );
        }
        return $a->getName() === $b->getName();
    }

    /**
     * @param \DateTimeZone|\DateTimeInterface $dateTimeOrTimeZone
     * @return bool
     */
    public static function isUtc($dateTimeOrTimeZone): bool
    {
        if ($dateTimeOrTimeZone instanceof \DateTimeInterface) {
            $dateTimeOrTimeZone = $dateTimeOrTimeZone->getTimezone();
        }
        if (!$dateTimeOrTimeZone instanceof \DateTimeZone) {
            throw new \InvalidArgumentException(
                'Parameter must either be instance of ' . \DateTimeZone::class . ' or ' . \DateTimeInterface::class . '.'
            );
        }
        return $dateTimeOrTimeZone->getName() === 'UTC';
    }
}
