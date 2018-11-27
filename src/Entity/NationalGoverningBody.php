<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-27
 * Time: 17:21
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use libphonenumber\PhoneNumber;
use Webmozart\Assert\Assert;

/**
 * Class NationalGoverningBody
 *
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="NationalGoverningBodyRepository")
 * @ORM\Table(name="ngbs")
 */
class NationalGoverningBody
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=64, unique=true)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="acronym", type="string", length=16, unique=true)
     *
     * @var string
     */
    private $acronym;

    /**
     * @ORM\Column(name="country", type="string", length=2)
     *
     * @var string
     */
    private $country;

    /**
     * @ORM\Column(name="email", type="string", length=128)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(name="website", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $website;

    /**
     * @ORM\Column(name="phone_number", type="phone_number", nullable=true)
     *
     * @var PhoneNumber|null
     */
    private $phoneNumber;

    /**
     * @ORM\Column(name="facebook_profile", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $facebookProfile;

    /**
     * @ORM\Column(name="twitter_profile", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $twitterProfile;

    /**
     * @ORM\Column(name="instagram_profile", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $instagramProfile;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     *
     * @var \DateTimeImmutable
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=128)
     * @Gedmo\Blameable(on="create")
     *
     * @var string
     */
    private $createdBy;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="update")
     *
     * @var \DateTimeImmutable
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=128)
     * @Gedmo\Blameable(on="update")
     *
     * @var string
     */
    private $updatedBy;

    /**
     * @param string           $id
     * @param string           $name
     * @param string           $acronym
     * @param string           $country
     * @param string           $email
     * @param string|null      $website
     * @param PhoneNumber|null $phoneNumber
     * @param string|null      $facebookProfile
     * @param string|null      $twitterProfile
     * @param string|null      $instagramProfile
     */
    public function __construct(
        string $id,
        string $name,
        string $acronym,
        string $country,
        string $email,
        ?string $website = null,
        ?PhoneNumber $phoneNumber = null,
        ?string $facebookProfile = null,
        ?string $twitterProfile = null,
        ?string $instagramProfile = null
    ) {
        Assert::uuid($id);
        Assert::lengthBetween($name, 1, 64);
        Assert::lengthBetween($acronym, 1, 64);
        Assert::length($country, 2);
        Assert::lengthBetween($email, 1, 128);
        Assert::nullOrLengthBetween($website, 1, 128);
        Assert::nullOrLengthBetween($facebookProfile, 1, 128);
        Assert::nullOrLengthBetween($twitterProfile, 1, 128);
        Assert::nullOrLengthBetween($instagramProfile, 1, 128);

        $this->id               = $id;
        $this->name             = $name;
        $this->acronym          = $acronym;
        $this->country          = $country;
        $this->email            = $email;
        $this->website          = $website;
        $this->phoneNumber      = $phoneNumber;
        $this->facebookProfile  = $facebookProfile;
        $this->twitterProfile   = $twitterProfile;
        $this->instagramProfile = $instagramProfile;
        $this->createdAt        = new \DateTime('now');
        $this->createdBy        = 'system';
        $this->updatedAt        = new \DateTime('now');
        $this->updatedBy        = 'system';
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAcronym(): string
    {
        return $this->acronym;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @return PhoneNumber|null
     */
    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    /**
     * @return string|null
     */
    public function getFacebookProfile(): ?string
    {
        return $this->facebookProfile;
    }

    /**
     * @return string|null
     */
    public function getTwitterProfile(): ?string
    {
        return $this->twitterProfile;
    }

    /**
     * @return string|null
     */
    public function getInstagramProfile(): ?string
    {
        return $this->instagramProfile;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return string
     */
    public function getUpdatedBy(): string
    {
        return $this->updatedBy;
    }
}
