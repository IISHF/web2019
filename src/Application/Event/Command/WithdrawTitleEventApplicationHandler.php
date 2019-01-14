<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:20
 */

namespace App\Application\Event\Command;

/**
 * Class WithdrawTitleEventApplicationHandler
 *
 * @package App\Application\Event\Command
 */
class WithdrawTitleEventApplicationHandler extends TitleEventApplicationCommandHandler
{
    /**
     * @param WithdrawTitleEventApplication $command
     */
    public function __invoke(WithdrawTitleEventApplication $command): void
    {
        $application = $this->getApplication($command->getId());
        $this->applicationRepository->delete($application);
    }
}
