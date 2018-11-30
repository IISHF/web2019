<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:28
 */

namespace App\Application\NationalGoverningBody\Command;

/**
 * Class DeleteNationalGoverningBodyHandler
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class DeleteNationalGoverningBodyHandler extends NationalGoverningBodyCommandHandler
{
    /**
     * @param DeleteNationalGoverningBody $command
     */
    public function __invoke(DeleteNationalGoverningBody $command): void
    {
        $ngb = $this->getNationalGoverningBody($command->getId());
        $this->repository->delete($ngb);
    }
}
