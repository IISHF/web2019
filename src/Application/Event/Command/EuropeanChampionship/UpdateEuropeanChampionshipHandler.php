<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:00
 */

namespace App\Application\Event\Command\EuropeanChampionship;

use App\Application\Event\Command\TitleEventCommandHandler;
use App\Domain\Model\Event\EuropeanChampionship;

/**
 * Class UpdateEuropeanChampionshipHandler
 *
 * @package App\Application\Event\Command\EuropeanChampionship
 */
class UpdateEuropeanChampionshipHandler extends TitleEventCommandHandler
{
    /**
     * @param UpdateEuropeanChampionship $command
     */
    public function __invoke(UpdateEuropeanChampionship $command): void
    {
        /** @var EuropeanChampionship $championship */
        $championship = $this->getEvent($command->getId(), EuropeanChampionship::class);
        $championship->setName($command->getName())
                     ->setSeason($command->getSeason())
                     ->setAgeGroup($command->getAgeGroup())
                     ->setPlannedLength($command->getPlannedLength())
                     ->setDescription($command->getDescription())
                     ->setTags($command->getTags());
        $this->eventRepository->save($championship);
    }
}
