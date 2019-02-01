<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:14
 */

namespace App\Application\Committee\Command;

/**
 * Class UpdateCommitteeHandler
 *
 * @package App\Application\Committee\Command
 */
class UpdateCommitteeHandler extends CommitteeCommandHandler
{
    /**
     * @param UpdateCommittee $command
     */
    public function __invoke(UpdateCommittee $command): void
    {
        $committee = $this->getCommittee($command->getId());
        $committee->setTitle($command->getTitle())
                  ->setSlug($this->findSuitableCommitteeSlug($command->getTitle(), $committee->getId()))
                  ->setDescription($command->getDescription());
        $this->committeeRepository->save($committee);
    }
}
