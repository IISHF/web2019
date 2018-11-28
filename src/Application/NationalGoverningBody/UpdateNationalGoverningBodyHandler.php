<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:27
 */

namespace App\Application\NationalGoverningBody;

/**
 * Class UpdateNationalGoverningBodyHandler
 *
 * @package App\Application\NationalGoverningBody
 */
class UpdateNationalGoverningBodyHandler extends NationalGoverningBodyCommandHandler
{
    /**
     * @param UpdateNationalGoverningBody $command
     */
    public function __invoke(UpdateNationalGoverningBody $command)
    {
        $ngb = $command->getNationalGoverningBody();
        $ngb->setName($command->getName())
            ->setAcronym($command->getAcronym())
            ->setSlug($command->getSlug())
            ->setIocCode($command->getIocCode())
            ->setCountry($command->getCountry())
            ->setEmail($command->getEmail())
            ->setWebsite($command->getWebsite())
            ->setPhoneNumber($command->getPhoneNumber())
            ->setFacebookProfile($command->getFacebookProfile())
            ->setTwitterProfile($command->getTwitterProfile())
            ->setInstagramProfile($command->getInstagramProfile());

        $this->repository->save($ngb);
    }
}
