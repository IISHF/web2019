<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 13:41
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Application\Common\Command\CommandDispatchingHandler;
use App\Application\Common\Command\CommandHandler;

/**
 * Class AddAttachmentsHandler
 *
 * @package App\Application\Article\Command
 */
class AddAttachmentsHandler implements CommandHandler, CommandDispatchingHandler
{
    use CommandDispatcher;

    /**
     * @param AddAttachments $command
     */
    public function __invoke(AddAttachments $command): void
    {
        $this->dispatchCommands($command);
    }
}
