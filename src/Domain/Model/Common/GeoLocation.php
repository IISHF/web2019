<?php
/**
 * GeoLocation
 */

namespace App\Domain\Model\Common;

use BadMethodCallException;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class GeoLocation
 *
 * @package App\Domain\Model\Common
 *
 * @ORM\Embeddable()
 */
class GeoLocation
{
    /**
     * @ORM\Column(name="latitude", type="decimal", precision=8, scale=6, nullable=true)
     *
     * @var float|null
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="decimal", precision=9, scale=6, nullable=true)
     *
     * @var float|null
     */
    private $longitude;

    /**
     * @param float $latitude
     * @param float $longitude
     * @return self
     */
    public static function with(float $latitude, float $longitude): self
    {
        return new self($latitude, $longitude);
    }

    /**
     * @return self
     */
    public static function null(): self
    {
        return new self(null, null);
    }

    /**
     * @param float|null $latitude
     * @param float|null $longitude
     */
    private function __construct(?float $latitude, ?float $longitude)
    {
        if ($latitude !== null && $longitude !== null) {
            Assert::range($latitude, -90, 90);
            Assert::range($longitude, -180, 180);
            $this->latitude  = round($latitude, 6);
            $this->longitude = round($longitude, 6);
        } else {
            $this->latitude  = null;
            $this->longitude = null;
        }
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        if (!$this->latitude) {
            throw new BadMethodCallException('Cannot call ' . __METHOD__ . ' when data is null.');
        }
        return (float)$this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        if (!$this->longitude) {
            throw new BadMethodCallException('Cannot call ' . __METHOD__ . ' when data is null.');
        }
        return (float)$this->longitude;
    }

    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return $this->latitude === null || $this->longitude === null;
    }
}
