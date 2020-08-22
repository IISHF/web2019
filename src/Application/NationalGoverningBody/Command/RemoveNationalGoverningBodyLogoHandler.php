<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\CommandDispatchingHandler;
use App\Application\Common\Command\RemoveFileCommandDispatcher;

/**
 * Class RemoveNationalGoverningBodyLogoHandler
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class RemoveNationalGoverningBodyLogoHandler extends NationalGoverningBodyCommandHandler implements
    CommandDispatchingHandler
{
    use RemoveFileCommandDispatcher;

    /**
     * @param RemoveNationalGoverningBodyLogo $command
     */
    public function __invoke(RemoveNationalGoverningBodyLogo $command): void
    {
        $ngb = $this->getNationalGoverningBody($command->getId());
        if ($this->dispatchRemoveFileIfExists($ngb->getLogo())) {
            $ngb->setLogo(null);
            $this->ngbRepository->save($ngb);
        }
    }
}
