<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:17
 */

namespace App\Application\Event\Command;

use App\Application\Event\Game\Command\UpdateScheduleTimeZone;
use App\Domain\Model\Event\Tournament;

/**
 * Class UpdateTournamentHandler
 *
 * @package App\Application\Event\Command
 */
class UpdateTournamentHandler extends TournamentCommandHandler
{
    /**
     * @param UpdateTournament $command
     */
    public function __invoke(UpdateTournament $command): void
    {
        $host       = $command->getHost();
        $tournament = $this->getEvent($command->getId());
        if (!$tournament instanceof Tournament) {
            throw new \InvalidArgumentException('Invalid event - ' . Tournament::class . ' required.');
        }
        $currentTimeZone = $tournament->getTimeZone();
        if ($currentTimeZone->getName() !== $command->getTimeZone()->getName()) {
            $this->dispatchCommand(UpdateScheduleTimeZone::update($tournament->getId(), $command->getTimeZone()));
        }

        $tournament->setName($command->getName())
                   ->setSeason($command->getSeason())
                   ->setAgeGroup($command->getAgeGroup())
                   ->setDate($command->getStartDate(), $command->getEndDate())
                   ->setVenue($this->getVenue($command->getVenue()))
                   ->setTimeZone($command->getTimeZone())
                   ->setTags($command->getTags());
        $tournament->getHost()
                   ->setClub($host->getClub())
                   ->setName($host->getName())
                   ->setEmail($host->getEmail())
                   ->setPhoneNumber($host->getPhoneNumber());
        $this->eventRepository->save($tournament);
    }
}
