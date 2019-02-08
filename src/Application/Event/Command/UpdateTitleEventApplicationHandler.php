<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:14
 */

namespace App\Application\Event\Command;

/**
 * Class UpdateTitleEventApplicationHandler
 *
 * @package App\Application\Event\Command
 */
class UpdateTitleEventApplicationHandler extends TitleEventApplicationCommandHandler
{
    /**
     * @param UpdateTitleEventApplication $command
     */
    public function __invoke(UpdateTitleEventApplication $command): void
    {
        $contact     = $command->getContact();
        $application = $this->getApplication($command->getId());
        $application->setApplicantClub($command->getApplicantClub())
                    ->setProposedDate($command->getProposedStartDate(), $command->getProposedEndDate())
                    ->setVenue($this->getVenue($command->getVenue()))
                    ->setTimeZone($command->getTimeZone());
        $application->getContact()
                    ->setName($contact->getName())
                    ->setEmail($contact->getEmail())
                    ->setPhoneNumber($contact->getPhoneNumber());
        $this->applicationRepository->save($application);
    }
}
