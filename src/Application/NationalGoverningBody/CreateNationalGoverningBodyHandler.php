<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:25
 */

namespace App\Application\NationalGoverningBody;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;

/**
 * Class CreateNationalGoverningBodyHandler
 *
 * @package App\Application\NationalGoverningBody
 */
class CreateNationalGoverningBodyHandler extends NationalGoverningBodyCommandHandler
{
    /**
     * @param CreateNationalGoverningBody $command
     */
    public function __invoke(CreateNationalGoverningBody $command)
    {
        $ngb = new NationalGoverningBody(
            $command->getId(),
            $command->getName(),
            $command->getAcronym(),
            $command->getSlug(),
            $command->getIocCode(),
            $command->getCountry(),
            $command->getEmail(),
            $command->getWebsite(),
            $command->getPhoneNumber(),
            $command->getFacebookProfile(),
            $command->getTwitterProfile(),
            $command->getInstagramProfile()
        );

        $this->repository->save($ngb);
    }
}
