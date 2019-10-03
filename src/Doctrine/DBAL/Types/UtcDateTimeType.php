<?php
/**
 * UtcDateTimeType
 */

namespace App\Doctrine\DBAL\Types;

use App\Domain\Common\Timezone;
use DateTime;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

/**
 * Class UtcDateTimeType
 *
 * @package App\Doctrine\DBAL\Types
 */
class UtcDateTimeType extends DateTimeType
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'datetime_utc';
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof DateTime) {
            $value->setTimezone(Timezone::getUtc());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTime
    {
        if ($value === null || $value instanceof DateTime) {
            return $value;
        }

        $converted = DateTime::createFromFormat($platform->getDateTimeFormatString(), $value, Timezone::getUtc());

        if (!$converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $converted;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
