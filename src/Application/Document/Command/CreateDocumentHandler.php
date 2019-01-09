<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:43
 */

namespace App\Application\Document\Command;

use App\Domain\Model\Document\Document;

/**
 * Class CreateDocumentHandler
 *
 * @package App\Application\Document\Command
 */
class CreateDocumentHandler extends DocumentCommandHandler
{
    /**
     * @param CreateDocument $command
     */
    public function __invoke(CreateDocument $command): void
    {
        $documentSlug = $this->findSuitableDocumentSlug($command->getTitle(), null);
        $document     = new Document(
            $command->getId(),
            $command->getTitle(),
            $documentSlug,
            $command->getTags()
        );
        $document->createVersion(
            $command->getVersionId(),
            $this->createFile($command->getFile(), $command->getTitle() . ' ' . $command->getVersion()),
            $command->getVersion(),
            $this->findSuitableDocumentVersionSlug($documentSlug, $command->getVersion(), null),
            $command->getValidFrom(),
            $command->getValidUntil()
        );
        $this->repository->save($document);
    }
}
