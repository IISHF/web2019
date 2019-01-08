<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 13:41
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\DelegatingMessageHandler;

/**
 * Class AddAttachmentsHandler
 *
 * @package App\Application\Article\Command
 */
class AddAttachmentsHandler extends DelegatingMessageHandler
{
    /**
     * @param AddAttachments $command
     */
    public function __invoke(AddAttachments $command): void
    {
        $this->dispatchCommands($command);
    }
}
