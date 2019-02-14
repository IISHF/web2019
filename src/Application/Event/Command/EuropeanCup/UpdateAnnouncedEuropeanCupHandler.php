<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:03
 */

namespace App\Application\Event\Command\EuropeanCup;

use App\Application\Event\Command\TitleEventCommandHandler;
use App\Application\Event\Game\Command\UpdateScheduleTimeZone;
use App\Domain\Model\Event\EuropeanCup;

/**
 * Class UpdateAnnouncedEuropeanCupHandler
 *
 * @package App\Application\Event\Command\EuropeanCup
 */
class UpdateAnnouncedEuropeanCupHandler extends TitleEventCommandHandler
{
    /**
     * @param UpdateAnnouncedEuropeanCup $command
     */
    public function __invoke(UpdateAnnouncedEuropeanCup $command): void
    {
        /** @var EuropeanCup $cup */
        $cup = $this->getEvent($command->getId(), EuropeanCup::class);
        $cup->setName($command->getName())
            ->setSeason($command->getSeason())
            ->setAgeGroup($command->getAgeGroup())
            ->setPlannedLength($command->getPlannedLength())
            ->setPlannedTeams($command->getPlannedTeams())
            ->setDescription($command->getDescription())
            ->setTags($command->getTags());
        if ($cup->getTimeZone()->getName() !== $command->getTimeZone()->getName()) {
            $this->dispatchCommand(UpdateScheduleTimeZone::update($cup->getId(), $command->getTimeZone()));
        }

        $cup->setVenue($this->getVenue($command->getVenue()))
            ->setDate($command->getStartDate(), $command->getEndDate(), $command->getTimeZone());
        $host = $command->getHost();
        $cup->getHost()
            ->setClub($host->getClub())
            ->setName($host->getName())
            ->setEmail($host->getEmail())
            ->setPhoneNumber($host->getPhoneNumber());
        $this->eventRepository->save($cup);
    }
}
