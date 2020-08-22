<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Staff\StaffMember;
use SplFileInfo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddStaffMemberImage
 *
 * @package App\Application\Staff\Command
 */
class AddStaffMemberImage
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
    private $image;

    /**
     * @param StaffMember $member
     * @param SplFileInfo $image
     * @return self
     */
    public static function addTo(StaffMember $member, SplFileInfo $image): self
    {
        return new self($member->getId(), $image);
    }

    /**
     * @param string      $id
     * @param SplFileInfo $image
     */
    private function __construct(string $id, SplFileInfo $image)
    {
        $this->id    = $id;
        $this->image = $image;
    }

    /**
     * @return SplFileInfo
     */
    public function getImage(): SplFileInfo
    {
        return $this->image;
    }
}
