<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:44
 */

namespace App\Application\Document\Command;

/**
 * Class DeleteDocumentHandler
 *
 * @package App\Application\Document\Command
 */
class DeleteDocumentHandler extends DocumentCommandHandler
{
    /**
     * @param DeleteDocument $command
     */
    public function __invoke(DeleteDocument $command): void
    {
        $document = $this->getDocument($command->getId());
        $this->documentRepository->delete($document);
    }
}
