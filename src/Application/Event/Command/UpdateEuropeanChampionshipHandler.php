<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:00
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EuropeanChampionship;

/**
 * Class UpdateEuropeanChampionshipHandler
 *
 * @package App\Application\Event\Command
 */
class UpdateEuropeanChampionshipHandler extends TitleEventCommandHandler
{
    /**
     * @param UpdateEuropeanChampionship $command
     */
    public function __invoke(UpdateEuropeanChampionship $command): void
    {
        $championship = $this->getEvent($command->getId());
        if (!$championship instanceof EuropeanChampionship) {
            throw new \InvalidArgumentException('Invalid event - ' . EuropeanChampionship::class . ' required.');
        }
        $championship->setName($command->getName())
                     ->setSeason($command->getSeason())
                     ->setAgeGroup($command->getAgeGroup())
                     ->setPlannedLength($command->getPlannedLength())
                     ->setDescription($command->getDescription())
                     ->setTags($command->getTags());
        if ($championship->isAnnounced() || $championship->isSanctioned()) {
            $championship->setVenue($this->getVenue($command->getVenue()))
                         ->setTimeZone($command->getTimeZone());
            $host = $command->getHost();
            $championship->getHost()
                         ->setClub($host->getClub())
                         ->setName($host->getName())
                         ->setEmail($host->getEmail())
                         ->setPhoneNumber($host->getPhoneNumber());
        }
        $this->eventRepository->save($championship);
    }
}
