<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:13
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\NationalGoverningBody\Validator\UniqueNationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use libphonenumber\PhoneNumber;

/**
 * Class UpdateNationalGoverningBody
 *
 * @package App\Application\NationalGoverningBody\Command
 *
 * @UniqueNationalGoverningBody()
 */
class UpdateNationalGoverningBody implements IdentifiesNationalGoverningBody
{
    use NationalGoverningBodyAware, MutableNationalGoverningBody, NationalGoverningBodyProperties;

    /**
     * @param NationalGoverningBody $nationalGoverningBody
     * @return self
     */
    public static function update(NationalGoverningBody $nationalGoverningBody): self
    {
        return new self(
            $nationalGoverningBody,
            $nationalGoverningBody->getName(),
            $nationalGoverningBody->getAcronym(),
            $nationalGoverningBody->getSlug(),
            $nationalGoverningBody->getIocCode(),
            $nationalGoverningBody->getCountry(),
            $nationalGoverningBody->getEmail(),
            $nationalGoverningBody->getWebsite(),
            $nationalGoverningBody->getPhoneNumber(),
            $nationalGoverningBody->getFacebookProfile(),
            $nationalGoverningBody->getTwitterProfile(),
            $nationalGoverningBody->getInstagramProfile()
        );
    }

    /**
     * @param NationalGoverningBody $nationalGoverningBody
     * @param string                $name
     * @param string                $acronym
     * @param string                $slug
     * @param string                $iocCode
     * @param string                $country
     * @param string                $email
     * @param string|null           $website
     * @param PhoneNumber|null      $phoneNumber
     * @param string|null           $facebookProfile
     * @param string|null           $twitterProfile
     * @param string|null           $instagramProfile
     */
    private function __construct(
        NationalGoverningBody $nationalGoverningBody,
        string $name,
        string $acronym,
        string $slug,
        string $iocCode,
        string $country,
        string $email,
        ?string $website,
        ?PhoneNumber $phoneNumber,
        ?string $facebookProfile,
        ?string $twitterProfile,
        ?string $instagramProfile
    ) {
        $this->nationalGoverningBody = $nationalGoverningBody;
        $this->name                  = $name;
        $this->acronym               = $acronym;
        $this->slug                  = $slug;
        $this->iocCode               = $iocCode;
        $this->country               = $country;
        $this->email                 = $email;
        $this->website               = $website;
        $this->phoneNumber           = $phoneNumber;
        $this->facebookProfile       = $facebookProfile;
        $this->twitterProfile        = $twitterProfile;
        $this->instagramProfile      = $instagramProfile;
    }
}
