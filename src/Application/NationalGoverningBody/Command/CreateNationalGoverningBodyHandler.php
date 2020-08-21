<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:25
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;

/**
 * Class CreateNationalGoverningBodyHandler
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class CreateNationalGoverningBodyHandler extends NationalGoverningBodyCommandHandler implements
    CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @param CreateNationalGoverningBody $command
     */
    public function __invoke(CreateNationalGoverningBody $command): void
    {
        $ngb = new NationalGoverningBody(
            $command->getId(),
            $command->getName(),
            $command->getAcronym(),
            $this->findSuitableSlug($command->getName(), null),
            $command->getIocCode(),
            $command->getCountry(),
            $command->getEmail(),
            $command->getWebsite(),
            $command->getPhoneNumber(),
            $command->getFacebookProfile(),
            $command->getTwitterProfile(),
            $command->getInstagramProfile(),
            $command->getTikTokProfile(),
            $command->getTelegramProfile()
        );

        $this->ngbRepository->save($ngb);

        if (($logo = $command->getLogo()) !== null) {
            $addLogo = AddNationalGoverningBodyLogo::addTo($ngb, $logo);
            $this->dispatchCommand($addLogo);
        }
    }
}
