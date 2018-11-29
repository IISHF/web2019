<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-27
 * Time: 17:21
 */

namespace App\Domain\Model\NationalGoverningBody;

use App\Domain\Common\Country;
use App\Domain\Model\Common\ChangeTracking;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Webmozart\Assert\Assert;

/**
 * Class NationalGoverningBody
 *
 * @package App\Domain\Model\NationalGoverningBody
 *
 * @ORM\Entity(repositoryClass="NationalGoverningBodyRepository")
 * @ORM\Table(name="national_governing_bodies")
 */
class NationalGoverningBody
{
    use ChangeTracking;

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
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(name="ioc_code", type="string", length=3, unique=true)
     *
     * @var string
     */
    private $iocCode;

    /**
     * @ORM\Column(name="country", type="string", length=2)
     *
     * @var string
     */
    private $country;

    /**
     * @ORM\Column(name="email", type="string", length=128, unique=true)
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
     * @param string           $id
     * @param string           $name
     * @param string           $acronym
     * @param string           $slug
     * @param string           $iocCode
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
        string $slug,
        string $iocCode,
        string $country,
        string $email,
        ?string $website = null,
        ?PhoneNumber $phoneNumber = null,
        ?string $facebookProfile = null,
        ?string $twitterProfile = null,
        ?string $instagramProfile = null
    ) {
        Assert::uuid($id);

        $this->id = $id;
        $this->setName($name)
             ->setAcronym($acronym)
             ->setSlug($slug)
             ->setIocCode($iocCode)
             ->setCountry($country)
             ->setEmail($email)
             ->setWebsite($website)
             ->setPhoneNumber($phoneNumber)
             ->setFacebookProfile($facebookProfile)
             ->setTwitterProfile($twitterProfile)
             ->setInstagramProfile($instagramProfile)
             ->initChangeTracking();
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
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        Assert::lengthBetween($name, 1, 64);
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAcronym(): string
    {
        return $this->acronym;
    }

    /**
     * @param string $acronym
     * @return $this
     */
    public function setAcronym(string $acronym): self
    {
        Assert::lengthBetween($acronym, 1, 16);
        $this->acronym = $acronym;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        Assert::regex($slug, '/^[0-9a-z-]+$/');
        Assert::lengthBetween($slug, 1, 128);
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getIocCode(): string
    {
        return $this->iocCode;
    }

    /**
     * @param string $iocCode
     * @return $this
     */
    public function setIocCode(string $iocCode): self
    {
        Assert::length($iocCode, 3);
        $this->iocCode = mb_strtoupper($iocCode, 'UTF-8');
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
        return Country::getCountryNameByCode($this->getCountry());
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        // @see \Symfony\Component\Validator\Constraints\EmailValidator::PATTERN_LOOSE
        Assert::regex($email, '/^.+\@\S+\.\S+$/');
        Assert::lengthBetween($email, 1, 128);
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     * @return $this
     */
    public function setWebsite(?string $website): self
    {
        // @see \Symfony\Component\Validator\Constraints\UrlValidator::PATTERN
        Assert::nullOrRegex(
            $website,
            '~^https?://([\pL\pN\pS\-\.])+(\.?([\pL\pN]|xn\-\-[\pL\pN-]+)+\.?)(:[0-9]+)?(?:/ (?:[\pL\pN\-._\~!$&\'()*+,;=:@]|%%[0-9A-Fa-f]{2})* )*$~ixu'
        );
        Assert::nullOrLengthBetween($website, 1, 128);
        $this->website = $website;
        return $this;
    }

    /**
     * @return PhoneNumber|null
     */
    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    /**
     * @param PhoneNumber|null $phoneNumber
     * @return $this
     */
    public function setPhoneNumber(?PhoneNumber $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFacebookProfile(): ?string
    {
        return $this->facebookProfile;
    }

    /**
     * @param string|null $facebookProfile
     * @return $this
     */
    public function setFacebookProfile(?string $facebookProfile): self
    {
        Assert::nullOrLengthBetween($facebookProfile, 1, 128);
        $this->facebookProfile = $facebookProfile;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTwitterProfile(): ?string
    {
        return $this->twitterProfile;
    }

    /**
     * @param string|null $twitterProfile
     * @return $this
     */
    public function setTwitterProfile(?string $twitterProfile): self
    {
        Assert::nullOrLengthBetween($twitterProfile, 1, 128);
        $this->twitterProfile = $twitterProfile;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstagramProfile(): ?string
    {
        return $this->instagramProfile;
    }

    /**
     * @param string|null $instagramProfile
     * @return $this
     */
    public function setInstagramProfile(?string $instagramProfile): self
    {
        Assert::nullOrLengthBetween($instagramProfile, 1, 128);
        $this->instagramProfile = $instagramProfile;
        return $this;
    }
}
