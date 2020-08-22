<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\AddFileCommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Domain\Model\File\FileRepository;
use App\Domain\Model\Staff\StaffMember;
use App\Domain\Model\Staff\StaffMemberRepository;

/**
 * Class AddStaffMemberImageHandler
 *
 * @package App\Application\Staff\Command
 */
class AddStaffMemberImageHandler extends StaffMemberCommandHandler implements CommandDispatchingHandler
{
    use AddFileCommandDispatcher;

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
        if ($this->dispatchRemoveFileIfExists($member->getImage())) {
            $member->setImage(null);
        }

        $image = $this->dispatchAddFile($command->getImage(), StaffMember::IMAGE_ORIGIN);
        $member->setImage($image);

        $this->memberRepository->save($member);
    }
}
