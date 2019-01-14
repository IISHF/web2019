<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:47
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EuropeanChampionship;

/**
 * Class CreateEuropeanChampionshipHandler
 *
 * @package App\Application\Event\Command
 */
class CreateEuropeanChampionshipHandler extends EventCommandHandler
{
    /**
     * @param CreateEuropeanChampionship $command
     */
    public function __invoke(CreateEuropeanChampionship $command): void
    {
        $championship = new EuropeanChampionship(
            $command->getId(),
            $command->getName(),
            $this->findSuitableSlug($command->getSeason(), $command->getName(), null),
            $command->getSeason(),
            $command->getAgeGroup(),
            $command->getPlannedLength(),
            $command->getDescription(),
            $command->getTags()
        );
        $this->eventRepository->save($championship);
    }
}
