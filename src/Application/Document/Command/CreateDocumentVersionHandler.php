<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:46
 */

namespace App\Application\Document\Command;

/**
 * Class CreateDocumentVersionHandler
 *
 * @package App\Application\Document\Command
 */
class CreateDocumentVersionHandler extends DocumentCommandHandler
{
    /**
     * @param CreateDocumentVersion $command
     */
    public function __invoke(CreateDocumentVersion $command): void
    {
        $document = $this->getDocument($command->getDocumentId());
        $version  = $document->createVersion(
            $command->getId(),
            $this->createFile($command->getFile()),
            $command->getVersion(),
            $this->findSuitableDocumentVersionSlug($document->getSlug(), $command->getVersion(), null),
            $command->getValidFrom(),
            $command->getValidUntil()
        );
        $this->repository->saveVersion($version);
    }
}
