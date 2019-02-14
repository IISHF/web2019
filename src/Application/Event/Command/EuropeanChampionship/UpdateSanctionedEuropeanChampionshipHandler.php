<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:00
 */

namespace App\Application\Event\Command\EuropeanChampionship;

use App\Application\Event\Command\TitleEventCommandHandler;
use App\Application\Event\Game\Command\UpdateScheduleTimeZone;
use App\Domain\Model\Event\EuropeanChampionship;

/**
 * Class UpdateSanctionedEuropeanChampionshipHandler
 *
 * @package App\Application\Event\Command\EuropeanChampionship
 */
class UpdateSanctionedEuropeanChampionshipHandler extends TitleEventCommandHandler
{
    /**
     * @param UpdateSanctionedEuropeanChampionship $command
     */
    public function __invoke(UpdateSanctionedEuropeanChampionship $command): void
    {
        /** @var EuropeanChampionship $championship */
        $championship = $this->getEvent($command->getId(), EuropeanChampionship::class);
        $championship->setName($command->getName())
                     ->setSeason($command->getSeason())
                     ->setAgeGroup($command->getAgeGroup())
                     ->setPlannedLength($command->getPlannedLength())
                     ->setDescription($command->getDescription())
                     ->setTags($command->getTags());
        if ($championship->getTimeZone()->getName() !== $command->getTimeZone()->getName()) {
            $this->dispatchCommand(UpdateScheduleTimeZone::update($championship->getId(), $command->getTimeZone()));
        }

        $championship->setVenue($this->getVenue($command->getVenue()))
                     ->setTimeZone($command->getTimeZone());
        $host = $command->getHost();
        $championship->getHost()
                     ->setClub($host->getClub())
                     ->setName($host->getName())
                     ->setEmail($host->getEmail())
                     ->setPhoneNumber($host->getPhoneNumber());

        $championship->setSanctionNumber($command->getSanctionNumber());
        $this->eventRepository->save($championship);
    }
}
