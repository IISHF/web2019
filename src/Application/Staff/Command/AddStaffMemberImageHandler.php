<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Application\File\Command\AddFile;
use App\Application\File\Command\RemoveFile;
use App\Domain\Model\File\FileRepository;
use App\Domain\Model\Staff\StaffMember;
use App\Domain\Model\Staff\StaffMemberRepository;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile as HttpUploadedFile;

/**
 * Class AddStaffMemberImageHandler
 *
 * @package App\Application\Staff\Command
 */
class AddStaffMemberImageHandler extends StaffMemberCommandHandler implements CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @param StaffMemberRepository $memberRepository
     * @param FileRepository        $fileRepository
     */
    public function __construct(StaffMemberRepository $memberRepository, FileRepository $fileRepository)
    {
        parent::__construct($memberRepository);
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param AddStaffMemberImage $command
     */
    public function __invoke(AddStaffMemberImage $command): void
    {
        $member = $this->getStaffMember($command->getId());
        if (($currentImage = $member->getImage()) !== null) {
            $removeFile = RemoveFile::remove($currentImage);
            $this->dispatchCommand($removeFile);
            $member->setImage(null);
        }

        $newImage     = $command->getImage();
        $originalName = null;
        if ($newImage instanceof HttpUploadedFile) {
            $originalName = $newImage->getClientOriginalName();
            $newImage     = $newImage->move(sys_get_temp_dir());
        }

        $addFile = AddFile::add($newImage, StaffMember::IMAGE_ORIGIN, $originalName);
        $this->dispatchCommand($addFile);

        $image = $this->fileRepository->findById($addFile->getId());
        if (!$image) {
            throw new RuntimeException('Something went wrong when saving the file');
        }
        $member->setImage($image);
        $this->memberRepository->save($member);
    }
}
