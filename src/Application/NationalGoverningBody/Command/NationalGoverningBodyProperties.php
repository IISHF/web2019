<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:57
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Domain\Common\Country;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait NationalGoverningBodyProperties
 *
 * @package App\Application\NationalGoverningBody\Command
 */
trait NationalGoverningBodyProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=64)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $name = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=16)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $acronym = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=3)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $iocCode = '';

    /**
     * @Assert\Type("string")
     * @Assert\Country()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $country = '';

    /**
     * @Assert\Type("string")
     * @Assert\Email(mode="strict", checkMX=true, checkHost=true)
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $email = '';

    /**
     * @Assert\Type("string")
     * @Assert\Url()
     * @Assert\Length(max=128)
     *
     * @var string|null
     */
    private $website;

    /**
     * @Assert\Type("libphonenumber\PhoneNumber")
     * @AssertPhoneNumber()
     * @Assert\Length(max=128)
     *
     * @var PhoneNumber|null
     */
    private $phoneNumber;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     *
     * @var string|null
     */
    private $facebookProfile;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex(pattern="/^[a-zA-Z0-9_]{1,20}$/")
     *
     * @see https://github.com/twitter/twitter-text/blob/master/js/src/regexp/validMentionOrList.js
     *
     * @var string|null
     */
    private $twitterProfile;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex(pattern="/^[A-Za-z0-9_](([A-Za-z0-9_]|(\.(?!\.))){0,28}[A-Za-z0-9_])?$/")
     *
     * @see https://blog.jstassen.com/2016/03/code-regex-for-instagram-username-and-hashtags/
     *
     * @var string|null
     */
    private $instagramProfile;

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
        $this->acronym = $acronym;
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
        $this->iocCode = $iocCode;
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
        $this->country = $country;
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
        $this->instagramProfile = $instagramProfile;
        return $this;
    }
}
