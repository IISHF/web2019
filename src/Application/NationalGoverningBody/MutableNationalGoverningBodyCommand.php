<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:58
 */

namespace App\Application\NationalGoverningBody;

use libphonenumber\PhoneNumber;

/**
 * Trait MutableNationalGoverningBodyCommand
 *
 * @package App\Application\NationalGoverningBody
 */
trait MutableNationalGoverningBodyCommand
{
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $acronym
     */
    public function setAcronym(string $acronym): void
    {
        $this->acronym = $acronym;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @param string $iocCode
     */
    public function setIocCode(string $iocCode): void
    {
        $this->iocCode = $iocCode;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string|null $website
     */
    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    /**
     * @param PhoneNumber|null $phoneNumber
     */
    public function setPhoneNumber(?PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @param string|null $facebookProfile
     */
    public function setFacebookProfile(?string $facebookProfile): void
    {
        $this->facebookProfile = $facebookProfile;
    }

    /**
     * @param string|null $twitterProfile
     */
    public function setTwitterProfile(?string $twitterProfile): void
    {
        $this->twitterProfile = $twitterProfile;
    }

    /**
     * @param string|null $instagramProfile
     */
    public function setInstagramProfile(?string $instagramProfile): void
    {
        $this->instagramProfile = $instagramProfile;
    }
}
