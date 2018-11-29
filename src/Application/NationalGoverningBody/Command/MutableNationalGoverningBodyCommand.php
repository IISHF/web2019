<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:58
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Domain\Common\Urlizer;
use libphonenumber\PhoneNumber;

/**
 * Trait MutableNationalGoverningBodyCommand
 *
 * @package App\Application\NationalGoverningBody\Command
 */
trait MutableNationalGoverningBodyCommand
{
    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        if (empty($this->slug)) {
            $this->setSlug(Urlizer::urlize($name));
        }
        return $this;
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
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
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
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        return $this;
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
     * @param string|null $website
     * @return $this
     */
    public function setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
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
     * @param string|null $facebookProfile
     * @return $this
     */
    public function setFacebookProfile(?string $facebookProfile): self
    {
        $this->facebookProfile = $facebookProfile;
        return $this;
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
     * @param string|null $instagramProfile
     * @return $this
     */
    public function setInstagramProfile(?string $instagramProfile): self
    {
        $this->instagramProfile = $instagramProfile;
        return $this;
    }
}
