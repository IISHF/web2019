<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Application\File\Command\AddFile;
use App\Application\File\Command\RemoveFile;
use App\Domain\Model\File\FileRepository;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile as HttpUploadedFile;

/**
 * Class AddNationalGoverningBodyLogoHandler
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class AddNationalGoverningBodyLogoHandler extends NationalGoverningBodyCommandHandler implements
    CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @var FileRepository
     */
    private $fileRepository;

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
        if (($currentLogo = $ngb->getLogo()) !== null) {
            $removeFile = RemoveFile::remove($currentLogo);
            $this->dispatchCommand($removeFile);
            $ngb->setLogo(null);
        }

        $newLogo      = $command->getLogo();
        $originalName = null;
        if ($newLogo instanceof HttpUploadedFile) {
            $originalName = $newLogo->getClientOriginalName();
            $newLogo      = $newLogo->move(sys_get_temp_dir());
        }

        $addFile = AddFile::add($newLogo, NationalGoverningBody::LOGO_ORIGIN, $originalName);
        $this->dispatchCommand($addFile);

        $logo = $this->fileRepository->findById($addFile->getId());
        if (!$logo) {
            throw new RuntimeException('Something went wrong when saving the file');
        }
        $ngb->setLogo($logo);
        $this->ngbRepository->save($ngb);
    }
}
