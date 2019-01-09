<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:13
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\UuidAware;
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
    use UuidAware, NationalGoverningBodyProperties, MutableNationalGoverningBody;

    /**
     * @param NationalGoverningBody $nationalGoverningBody
     * @return self
     */
    public static function update(NationalGoverningBody $nationalGoverningBody): self
    {
        return new self(
            $nationalGoverningBody->getId(),
            $nationalGoverningBody->getName(),
            $nationalGoverningBody->getAcronym(),
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
     * @param string           $id
     * @param string           $name
     * @param string           $acronym
     * @param string           $iocCode
     * @param string           $country
     * @param string           $email
     * @param string|null      $website
     * @param PhoneNumber|null $phoneNumber
     * @param string|null      $facebookProfile
     * @param string|null      $twitterProfile
     * @param string|null      $instagramProfile
     */
    private function __construct(
        string $id,
        string $name,
        string $acronym,
        string $iocCode,
        string $country,
        string $email,
        ?string $website,
        ?PhoneNumber $phoneNumber,
        ?string $facebookProfile,
        ?string $twitterProfile,
        ?string $instagramProfile
    ) {
        $this->id               = $id;
        $this->name             = $name;
        $this->acronym          = $acronym;
        $this->iocCode          = $iocCode;
        $this->country          = $country;
        $this->email            = $email;
        $this->website          = $website;
        $this->phoneNumber      = $phoneNumber;
        $this->facebookProfile  = $facebookProfile;
        $this->twitterProfile   = $twitterProfile;
        $this->instagramProfile = $instagramProfile;
    }
}
