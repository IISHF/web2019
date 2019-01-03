<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 13:23
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\ArticleImage;

/**
 * Class AddImageHandler
 *
 * @package App\Application\Article\Command
 */
class AddImageHandler extends AttachmentCommandHandler
{
    /**
     * @param AddImage $command
     */
    public function __invoke(AddImage $command): void
    {
        $article = $this->getArticle($command->getArticleId());
        $file    = $command->getFile();
        $image   = ArticleImage::create(
            $command->isPrimaryImage(),
            $command->getId(),
            $article,
            self::guessMimeType($file),
            $command->getCaption()
        );
        $this->repository->saveAttachment($image);
    }
}
