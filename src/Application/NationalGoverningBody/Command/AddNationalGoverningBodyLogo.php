<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use SplFileInfo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddNationalGoverningBodyLogo
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class AddNationalGoverningBodyLogo
{
    use IdAware;

    /**
     * @Assert\File(
     *      maxSize="4M",
     *      mimeTypes={
     *          "image/*"
     *      }
     * )
     * @Assert\Type("SplFileInfo")
     * @Assert\NotNull()
     *
     * @var SplFileInfo
     */
    private $logo;

    /**
     * @param NationalGoverningBody $nationalGoverningBody
     * @param SplFileInfo           $logo
     * @return self
     */
    public static function addTo(NationalGoverningBody $nationalGoverningBody, SplFileInfo $logo): self
    {
        return new self($nationalGoverningBody->getId(), $logo);
    }

    /**
     * @param string      $id
     * @param SplFileInfo $logo
     */
    private function __construct(string $id, SplFileInfo $logo)
    {
        $this->id   = $id;
        $this->logo = $logo;
    }

    /**
     * @return SplFileInfo
     */
    public function getLogo(): SplFileInfo
    {
        return $this->logo;
    }
}
