<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:50
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EuropeanCup;

/**
 * Class CreateEuropeanCupHandler
 *
 * @package App\Application\Event\Command
 */
class CreateEuropeanCupHandler extends EventCommandHandler
{
    /**
     * @param CreateEuropeanCup $command
     */
    public function __invoke(CreateEuropeanCup $command): void
    {
        $cup = new EuropeanCup(
            $command->getId(),
            $command->getName(),
            $this->findSuitableSlug($command->getSeason(), $command->getName(), null),
            $command->getSeason(),
            $command->getAgeGroup(),
            $command->getPlannedLength(),
            $command->getPlannedTeams(),
            $command->getDescription(),
            $command->getTags()
        );
        $this->eventRepository->save($cup);
    }
}
