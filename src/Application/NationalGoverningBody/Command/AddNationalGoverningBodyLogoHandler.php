<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\AddFileCommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Domain\Model\File\FileRepository;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;

/**
 * Class AddNationalGoverningBodyLogoHandler
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class AddNationalGoverningBodyLogoHandler extends NationalGoverningBodyCommandHandler implements
    CommandDispatchingHandler
{
    use AddFileCommandDispatcher;

    /**
     * @param NationalGoverningBodyRepository $ngbRepository
     * @param FileRepository                  $fileRepository
     */
    public function __construct(NationalGoverningBodyRepository $ngbRepository, FileRepository $fileRepository)
    {
        parent::__construct($ngbRepository);
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param AddNationalGoverningBodyLogo $command
     */
    public function __invoke(AddNationalGoverningBodyLogo $command): void
    {
        $ngb = $this->getNationalGoverningBody($command->getId());
        if ($this->dispatchRemoveFileIfExists($ngb->getLogo())) {
            $ngb->setLogo(null);
        }

        $logo = $this->dispatchAddFile($command->getLogo(), NationalGoverningBody::LOGO_ORIGIN);
        $ngb->setLogo($logo);

        $this->ngbRepository->save($ngb);
    }
}
