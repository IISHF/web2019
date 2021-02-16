<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:27
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;

/**
 * Class UpdateNationalGoverningBodyHandler
 *
 * @package App\Application\NationalGoverningBody
 */
class UpdateNationalGoverningBodyHandler extends NationalGoverningBodyCommandHandler implements
    CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @param UpdateNationalGoverningBody $command
     */
    public function __invoke(UpdateNationalGoverningBody $command): void
    {
        $ngb = $this->getNationalGoverningBody($command->getId());
        $ngb->setName($command->getName())
            ->setAcronym($command->getAcronym())
            ->setSlug($this->findSuitableSlug($command->getName(), $ngb->getId()))
            ->setIocCode($command->getIocCode())
            ->setCountry($command->getCountry())
            ->setEmail($command->getEmail())
            ->setWebsite($command->getWebsite())
            ->setPhoneNumber($command->getPhoneNumber())
            ->setFacebookProfile($command->getFacebookProfile())
            ->setTwitterProfile($command->getTwitterProfile())
            ->setInstagramProfile($command->getInstagramProfile())
            ->setTikTokProfile($command->getTikTokProfile())
            ->setTelegramProfile($command->getTelegramProfile())
            ->setYouTubeChannel($command->getYouTubeChannel())
            ->setYouTubeProfile($command->getYouTubeProfile())
            ->setVimeoProfile($command->getVimeoProfile());

        $this->ngbRepository->save($ngb);

        if (($logo = $command->getLogo()) !== null) {
            $addLogo = AddNationalGoverningBodyLogo::addTo($ngb, $logo);
            $this->dispatchCommand($addLogo);
        } elseif ($command->removeLogo()) {
            $removeLogo = RemoveNationalGoverningBodyLogo::removeFrom($ngb);
            $this->dispatchCommand($removeLogo);
        }
    }
}
