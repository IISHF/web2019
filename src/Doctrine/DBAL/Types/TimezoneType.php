<?php
/**
 * TimezoneType
 */

namespace App\Doctrine\DBAL\Types;

use App\Domain\Common\Timezone;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

/**
 * Class TimezoneType
 *
 * @package App\Doctrine\DBAL\Types
 */
class TimezoneType extends Type
{
    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'timezone';
    }

    /**
     * {@inheritDoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof DateTimeZone) {
            return $value->getName();
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTimeZone']);
    }

    /**
     * {@inheritDoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeZone
    {
        if ($value === null || $value instanceof DateTimeZone) {
            return $value;
        }

        if (!in_array($value, DateTimeZone::listIdentifiers(), true)) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return Timezone::get($value);
    }

    /**
     * {@inheritDoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL(
            [
                'length' => 64,
                'fixed'  => false,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
