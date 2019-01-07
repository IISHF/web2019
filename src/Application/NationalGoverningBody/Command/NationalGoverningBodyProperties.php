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
    protected $name = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=16)
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $acronym = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(min=3, max=3)
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $iocCode = '';

    /**
     * @Assert\Type("string")
     * @Assert\Country()
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $country = '';

    /**
     * @Assert\Type("string")
     * @Assert\Email(mode="strict", checkMX=true, checkHost=true)
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    protected $email = '';

    /**
     * @Assert\Type("string")
     * @Assert\Url()
     * @Assert\Length(max=128)
     *
     * @var string|null
     */
    protected $website;

    /**
     * @Assert\Type("libphonenumber\PhoneNumber")
     * @AssertPhoneNumber()
     * @Assert\Length(max=128)
     *
     * @var PhoneNumber|null
     */
    protected $phoneNumber;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     *
     * @var string|null
     */
    protected $facebookProfile;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex"/^[a-zA-Z0-9_]{1,20}$/")
     *
     * @see https://github.com/twitter/twitter-text/blob/master/js/src/regexp/validMentionOrList.js
     *
     * @var string|null
     */
    protected $twitterProfile;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex("/^[A-Za-z0-9_](([A-Za-z0-9_]|(\.(?!\.))){0,28}[A-Za-z0-9_])?$/")
     *
     * @see https://blog.jstassen.com/2016/03/code-regex-for-instagram-username-and-hashtags/
     *
     * @var string|null
     */
    protected $instagramProfile;

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
    public function getIocCode(): string
    {
        return $this->iocCode;
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
}
