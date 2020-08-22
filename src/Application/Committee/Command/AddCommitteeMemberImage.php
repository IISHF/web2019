<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Committee\CommitteeMember;
use SplFileInfo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AddCommitteeMemberImage
 *
 * @package App\Application\Committee\Command
 */
class AddCommitteeMemberImage
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
     * @param CommitteeMember $member
     * @param SplFileInfo     $image
     * @return self
     */
    public static function addTo(CommitteeMember $member, SplFileInfo $image): self
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
