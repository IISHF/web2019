<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:28
 */

namespace App\Application\NationalGoverningBody;

/**
 * Class DeleteNationalGoverningBodyHandler
 *
 * @package App\Application\NationalGoverningBody
 */
class DeleteNationalGoverningBodyHandler extends NationalGoverningBodyCommandHandler
{
    /**
     * @param DeleteNationalGoverningBody $command
     */
    public function __invoke(DeleteNationalGoverningBody $command)
    {
        $this->repository->delete($command->getNationalGoverningBody());
    }
}
