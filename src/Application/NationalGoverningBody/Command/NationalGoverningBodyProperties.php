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
use SplFileInfo;
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
     * @Assert\Email(mode="strict")
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
     * @Assert\Regex(pattern="/^(?<!@)[a-zA-Z0-9_]{1,20}$/")
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
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex(pattern="/^[0-9A-Za-z_]{2,24}$/")
     *
     * @see https://www.wikidata.org/wiki/Wikidata:Property_proposal/TikTok_username
     *
     * @var string|null
     */
    private $tikTokProfile;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex(pattern="/^[0-9A-Za-z_]{5,32}$/")
     *
     * @see https://core.telegram.org/method/account.checkUsername
     *
     * @var string|null
     */
    private $telegramProfile;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex(pattern="/^[A-z0-9-\_]+$/")
     *
     * @see https://awesomeopensource.com/project/lorey/social-media-profiles-regexs#youtube
     *
     * @var string|null
     */
    private $youTubeChannel;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex(pattern="/^[A-z0-9]+$/")
     *
     * @see https://awesomeopensource.com/project/lorey/social-media-profiles-regexs#youtube
     *
     * @var string|null
     */
    private $youTubeProfile;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Regex(pattern="/^user[0-9]+$/")
     *
     * @see https://awesomeopensource.com/project/lorey/social-media-profiles-regexs#vimeo
     *
     * @var string|null
     */
    private $vimeoProfile;

    /**
     * @Assert\File(
     *      maxSize="4M",
     *      mimeTypes={
     *          "image/*"
     *      }
     * )
     * @Assert\Type("SplFileInfo")
     *
     * @var SplFileInfo|null
     */
    private $logo;

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
        $this->vimeoProfile = $vimeoProfile;
        return $this;
    }

    /**
     * @return SplFileInfo|null
     */
    public function getLogo(): ?SplFileInfo
    {
        return $this->logo;
    }

    /**
     * @param SplFileInfo|null $logo
     * @return $this
     */
    public function setLogo(?SplFileInfo $logo): self
    {
        $this->logo = $logo;
        return $this;
    }
}
