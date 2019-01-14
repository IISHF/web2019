<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:47
 */

namespace App\Application\Document\Command;

/**
 * Class UpdateDocumentVersionHandler
 *
 * @package App\Application\Document\Command
 */
class UpdateDocumentVersionHandler extends DocumentCommandHandler
{
    /**
     * @param UpdateDocumentVersion $command
     */
    public function __invoke(UpdateDocumentVersion $command): void
    {
        $version = $this->getDocumentVersion($command->getId());
        $version->setVersion($command->getVersion())
                ->setSlug(
                    $this->findSuitableDocumentVersionSlug(
                        $version->getDocument()->getSlug(),
                        $command->getVersion(),
                        $version->getId()
                    )
                )
                ->setValidity($command->getValidFrom(), $command->getValidUntil());
        $this->documentRepository->saveVersion($version);
    }
}
