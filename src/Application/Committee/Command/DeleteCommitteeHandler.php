<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:14
 */

namespace App\Application\Committee\Command;

/**
 * Class DeleteCommitteeHandler
 *
 * @package App\Application\Committee\Command
 */
class DeleteCommitteeHandler extends CommitteeCommandHandler
{
    /**
     * @param DeleteCommittee $command
     */
    public function __invoke(DeleteCommittee $command): void
    {
        $committee = $this->getCommittee($command->getId());
        $this->committeeRepository->delete($committee);
    }
}
