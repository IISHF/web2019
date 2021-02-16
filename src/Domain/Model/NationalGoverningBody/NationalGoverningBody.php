<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-27
 * Time: 17:21
 */

namespace App\Domain\Model\NationalGoverningBody;

use App\Domain\Common\Country;
use App\Domain\Model\Common\AssociationOne;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasEmail;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\MayHavePhoneNumber;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\File\File;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Webmozart\Assert\Assert;

/**
 * Class NationalGoverningBody
 *
 * @package App\Domain\Model\NationalGoverningBody
 *
 * @ORM\Entity(repositoryClass="NationalGoverningBodyRepository")
 * @ORM\Table(
 *      name="national_governing_bodies",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_ngb_name", columns={"name"}),
 *          @ORM\UniqueConstraint(name="uniq_ngb_email", columns={"email"}),
 *          @ORM\UniqueConstraint(name="uniq_ngb_acronym", columns={"acronym"}),
 *          @ORM\UniqueConstraint(name="uniq_ngb_slug", columns={"slug"}),
 *          @ORM\UniqueConstraint(name="uniq_ngb_ioc_code", columns={"ioc_code"}),
 *      }
 * )
 */
class NationalGoverningBody
{
    use HasId, CreateTracking, UpdateTracking, HasEmail, MayHavePhoneNumber, AssociationOne;

    public const LOGO_ORIGIN = 'com.iishf.ngb.logo';

    /**
     * @ORM\Column(name="name", type="string", length=64)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="acronym", type="string", length=16)
     *
     * @var string
     */
    private $acronym;

    /**
     * @ORM\Column(name="slug", type="string", length=128)
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(name="ioc_code", type="string", length=3)
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
     * @ORM\Column(name="website", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $website;

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
     * @ORM\Column(name="tik_tok_profile", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $tikTokProfile;

    /**
     * @ORM\Column(name="telegram_profile", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $telegramProfile;

    /**
     * @ORM\Column(name="you_tube_channel", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $youTubeChannel;

    /**
     * @ORM\Column(name="you_tube_profile", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $youTubeProfile;

    /**
     * @ORM\Column(name="vimeo_profile", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $vimeoProfile;

    /**
     * @ORM\OneToOne(targetEntity="\App\Domain\Model\File\File")
     * @ORM\JoinColumn(name="logo_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     *
     * @var File|null
     */
    private $logo;

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
        ?PhoneNumber $phoneNumber = null
    ) {
        $this->setId($id)
             ->setName($name)
             ->setAcronym($acronym)
             ->setSlug($slug)
             ->setIocCode($iocCode)
             ->setCountry($country)
             ->setEmail($email)
             ->setWebsite($website)
             ->setPhoneNumber($phoneNumber)
             ->initCreateTracking()
             ->initUpdateTracking();
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
        $country = mb_strtoupper($country, 'UTF-8');
        Assert::length($country, 2);
        Country::assertValidCountry($country);
        $this->country = $country;
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

    /**
     * @return string|null
     */
    public function getTikTokProfile(): ?string
    {
        return $this->tikTokProfile;
    }

    /**
     * @param string|null $tikTokProfile
     * @return $this
     */
    public function setTikTokProfile(?string $tikTokProfile): self
    {
        Assert::nullOrLengthBetween($tikTokProfile, 1, 128);
        $this->tikTokProfile = $tikTokProfile;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTelegramProfile(): ?string
    {
        return $this->telegramProfile;
    }

    /**
     * @param string|null $telegramProfile
     * @return $this
     */
    public function setTelegramProfile(?string $telegramProfile): self
    {
        Assert::nullOrLengthBetween($telegramProfile, 1, 128);
        $this->telegramProfile = $telegramProfile;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getYouTubeChannel(): ?string
    {
        return $this->youTubeChannel;
    }

    /**
     * @param string|null $youTubeChannel
     * @return $this
     */
    public function setYouTubeChannel(?string $youTubeChannel): self
    {
        Assert::nullOrLengthBetween($youTubeChannel, 1, 128);
        $this->youTubeChannel = $youTubeChannel;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getYouTubeProfile(): ?string
    {
        return $this->youTubeProfile;
    }

    /**
     * @param string|null $youTubeProfile
     * @return $this
     */
    public function setYouTubeProfile(?string $youTubeProfile): self
    {
        Assert::nullOrLengthBetween($youTubeProfile, 1, 128);
        $this->youTubeProfile = $youTubeProfile;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVimeoProfile(): ?string
    {
        return $this->vimeoProfile;
    }

    /**
     * @param string|null $vimeoProfile
     * @return $this
     */
    public function setVimeoProfile(?string $vimeoProfile): self
    {
        Assert::nullOrLengthBetween($vimeoProfile, 1, 128);
        $this->vimeoProfile = $vimeoProfile;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getLogo(): ?File
    {
        return $this->logo;
    }

    /**
     * @param File|null $logo
     * @return $this
     */
    public function setLogo(?File $logo): self
    {
        return $this->setRelatedEntity($this->logo, $logo);
    }
}
