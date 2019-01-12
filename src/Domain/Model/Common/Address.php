<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 14:57
 */

namespace App\Domain\Model\Common;

use App\Domain\Common\Country;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Address
 *
 * @package App\Domain\Model\Common
 *
 * @ORM\Embeddable()
 */
class Address
{
    /**
     * @ORM\Column(name="address1", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $address1;

    /**
     * @ORM\Column(name="address2", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $address2;

    /**
     * @ORM\Column(name="state", type="string", length=64, nullable=true)
     *
     * @var string|null
     */
    private $state;

    /**
     * @ORM\Column(name="postal_code", type="string", length=32, nullable=true)
     *
     * @var string|null
     */
    private $postalCode;

    /**
     * @ORM\Column(name="city", type="string", length=128)
     *
     * @var string
     */
    private $city;

    /**
     * @ORM\Column(name="country", type="string", length=2)
     *
     * @var string
     */
    private $country;

    /**
     * @param string|null $address1
     * @param string|null $address2
     * @param string|null $state
     * @param string|null $postalCode
     * @param string      $city
     * @param string      $country
     */
    public function __construct(
        ?string $address1,
        ?string $address2,
        ?string $state,
        ?string $postalCode,
        string $city,
        string $country
    ) {
        $this->setAddress($address1, $address2)
             ->setState($state)
             ->setPostalCode($postalCode)
             ->setCity($city)
             ->setCountry($country);
    }

    /**
     * @return string|null
     */
    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    /**
     * @return string|null
     */
    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    /**
     * @param string|null $address1
     * @param string|null $address2
     * @return $this
     */
    public function setAddress(?string $address1, ?string $address2): self
    {
        if ($address1 === null && $address2 !== null) {
            $address1 = $address2;
            $address2 = null;
        }
        Assert::nullOrLengthBetween($address1, 1, 128);
        Assert::nullOrLengthBetween($address2, 1, 128);
        $this->address1 = $address1;
        $this->address2 = $address2;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     * @return $this
     */
    public function setState(?string $state): self
    {
        Assert::nullOrLengthBetween($state, 1, 64);
        $this->state = $state;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    /**
     * @param string|null $postalCode
     * @return $this
     */
    public function setPostalCode(?string $postalCode): self
    {
        Assert::nullOrLengthBetween($postalCode, 1, 32);
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Address
     */
    public function setCity(string $city): self
    {
        Assert::lengthBetween($city, 1, 128);
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        Assert::length($country, 2);
        $this->country = mb_strtoupper($country, 'UTF-8');
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryName(): string
    {
        return Country::getCountryNameByCode($this->country);
    }

    /**
     * @return string
     */
    public function getGeocodeString(): string
    {
        $address = [];
        if ($this->address1) {
            $address[] = $this->address1;
        }
        if ($this->address2) {
            $address[] = $this->address2;
        }
        if ($this->state) {
            $address[] = $this->state;
        }
        $city = $this->city;
        if ($this->postalCode) {
            $city = $this->postalCode . ' $city';
        }
        $address[] = $city;
        $address[] = $this->getCountryName();
        return implode(', ', $address);
    }
}
