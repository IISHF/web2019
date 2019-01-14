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
        $document = ArticleDocument::create(
            $command->getId(),
            $article,
            $this->createFile(ArticleDocument::FILE_ORIGIN, $command->getFile()),
            $command->getTitle()
        );
        $this->articleRepository->saveAttachment($document);
    }
}
