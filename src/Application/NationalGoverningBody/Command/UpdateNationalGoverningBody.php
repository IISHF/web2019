<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:13
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\IdAware;
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
    use IdAware, NationalGoverningBodyProperties;

    /**
     * @var bool
     */
    private bool $hasLogo;

    /**
     * @var bool
     */
    private bool $removeLogo = false;

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
            $nationalGoverningBody->getInstagramProfile(),
            $nationalGoverningBody->getLogo() !== null
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
     * @param bool             $hasLogo
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
        ?string $instagramProfile,
        bool $hasLogo
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
        $this->hasLogo          = $hasLogo;
    }

    /**
     * @return bool
     */
    public function hasLogo(): bool
    {
        return $this->hasLogo;
    }

    /**
     * @return bool
     */
    public function removeLogo(): bool
    {
        return $this->removeLogo;
    }

    /**
     * @param bool $removeLogo
     * @return $this
     */
    public function setRemoveLogo(bool $removeLogo): self
    {
        $this->removeLogo = $removeLogo;
        return $this;
    }
}
