<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:17
 */

namespace App\Application\Event\Command\Tournament;

use App\Application\Event\Game\Command\UpdateScheduleTimeZone;
use App\Domain\Common\DateTime;
use App\Domain\Model\Event\Tournament;

/**
 * Class UpdateTournamentHandler
 *
 * @package App\Application\Event\Command\Tournament
 */
class UpdateTournamentHandler extends TournamentCommandHandler
{
    /**
     * @param UpdateTournament $command
     */
    public function __invoke(UpdateTournament $command): void
    {
        /** @var Tournament $tournament */
        $tournament = $this->getEvent($command->getId(), Tournament::class);
        if (!DateTime::isTimeZoneEqual($tournament->getTimeZone(), $command->getTimeZone())) {
            $this->dispatchCommand(UpdateScheduleTimeZone::update($tournament->getId(), $command->getTimeZone()));
        }

        $tournament->setName($command->getName())
                   ->setSeason($command->getSeason())
                   ->setAgeGroup($command->getAgeGroup())
                   ->setDate($command->getStartDate(), $command->getEndDate(), $command->getTimeZone())
                   ->setVenue($this->getVenue($command->getVenue()))
                   ->setTags($command->getTags());

        $host = $command->getHost();
        $tournament->getHost()
                   ->setClub($host->getClub())
                   ->setName($host->getName())
                   ->setEmail($host->getEmail())
                   ->setPhoneNumber($host->getPhoneNumber());
        $this->eventRepository->save($tournament);
    }
}
