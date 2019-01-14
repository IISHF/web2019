<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:44
 */

namespace App\Application\Document\Command;

/**
 * Class UpdateDocumentHandler
 *
 * @package App\Application\Document\Command
 */
class UpdateDocumentHandler extends DocumentCommandHandler
{
    /**
     * @param UpdateDocument $command
     */
    public function __invoke(UpdateDocument $command): void
    {
        $document = $this->getDocument($command->getId());
        $document->setTitle($command->getTitle())
                 ->setSlug($this->findSuitableDocumentSlug($command->getTitle(), $document->getId()))
                 ->setTags($command->getTags());
        $this->documentRepository->save($document);
    }
}
