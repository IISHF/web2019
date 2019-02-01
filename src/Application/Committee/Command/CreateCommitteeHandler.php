<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:13
 */

namespace App\Application\Committee\Command;

use App\Domain\Model\Committee\Committee;

/**
 * Class CreateCommitteeHandler
 *
 * @package App\Application\Committee\Command
 */
class CreateCommitteeHandler extends CommitteeCommandHandler
{
    /**
     * @param CreateCommittee $command
     */
    public function __invoke(CreateCommittee $command): void
    {
        $committeeSlug = $this->findSuitableCommitteeSlug($command->getTitle(), null);
        $committee     = new Committee(
            $command->getId(),
            $command->getTitle(),
            $committeeSlug,
            $command->getDescription()
        );
        $this->committeeRepository->save($committee);
    }
}
