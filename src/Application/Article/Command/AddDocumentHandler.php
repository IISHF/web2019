<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 13:23
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\ArticleDocument;

/**
 * Class AddDocumentHandler
 *
 * @package App\Application\Article\Command
 */
class AddDocumentHandler extends AttachmentCommandHandler
{
    /**
     * @param AddDocument $command
     */
    public function __invoke(AddDocument $command): void
    {
        $article  = $this->getArticle($command->getArticleId());
        $file     = $command->getFile();
        $document = ArticleDocument::create(
            $command->getId(),
            $article,
            self::guessMimeType($file),
            $command->getTitle()
        );
        $this->repository->saveAttachment($document);
    }
}
