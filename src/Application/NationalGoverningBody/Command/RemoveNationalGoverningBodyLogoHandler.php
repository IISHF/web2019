<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Application\File\Command\RemoveFile;

/**
 * Class RemoveNationalGoverningBodyLogoHandler
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class RemoveNationalGoverningBodyLogoHandler extends NationalGoverningBodyCommandHandler implements
    CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @param RemoveNationalGoverningBodyLogo $command
     */
    public function __invoke(RemoveNationalGoverningBodyLogo $command): void
    {
        $ngb = $this->getNationalGoverningBody($command->getId());
        if (($logo = $ngb->getLogo()) !== null) {
            $removeFile = RemoveFile::remove($logo);
            $this->dispatchCommand($removeFile);
            $ngb->setLogo(null);
            $this->ngbRepository->save($ngb);
        }
    }
}
