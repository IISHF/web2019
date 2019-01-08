<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:46
 */

namespace App\Application\Document\Command;

/**
 * Class DeleteDocumentVersionHandler
 *
 * @package App\Application\Document\Command
 */
class DeleteDocumentVersionHandler extends DocumentCommandHandler
{
    /**
     * @param DeleteDocumentVersion $command
     */
    public function __invoke(DeleteDocumentVersion $command): void
    {

    }
}
