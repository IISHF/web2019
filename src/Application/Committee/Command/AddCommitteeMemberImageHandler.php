<?php
/*
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\AddFileCommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Domain\Model\Committee\CommitteeMember;
use App\Domain\Model\Committee\CommitteeRepository;
use App\Domain\Model\File\FileRepository;

/**
 * Class AddCommitteeMemberImageHandler
 *
 * @package App\Application\Committee\Command
 */
class AddCommitteeMemberImageHandler extends CommitteeCommandHandler implements CommandDispatchingHandler
{
    use AddFileCommandDispatcher;

    /**
     * @param CommitteeRepository $committeeRepository
     * @param FileRepository      $fileRepository
     */
    public function __construct(CommitteeRepository $committeeRepository, FileRepository $fileRepository)
    {
        parent::__construct($committeeRepository);
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param AddCommitteeMemberImage $command
     */
    public function __invoke(AddCommitteeMemberImage $command): void
    {
        $member = $this->getCommitteeMember($command->getId());
        if ($this->dispatchRemoveFileIfExists($member->getImage())) {
            $member->setImage(null);
        }

        $image = $this->dispatchAddFile($command->getImage(), CommitteeMember::IMAGE_ORIGIN);
        $member->setImage($image);

        $this->committeeRepository->saveMember($member);
    }
}
