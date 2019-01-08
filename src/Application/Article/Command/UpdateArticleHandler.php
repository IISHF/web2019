<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:25
 */

namespace App\Application\Article\Command;

use App\Application\Article\ArticleBody;
use App\Domain\Model\Article\ArticleRepository;
use App\Domain\Model\File\FileRepository;

/**
 * Class UpdateArticleHandler
 *
 * @package App\Application\Article\Command
 */
class UpdateArticleHandler extends ArticleCommandHandler
{
    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @param ArticleRepository $repository
     * @param FileRepository    $fileRepository
     */
    public function __construct(ArticleRepository $repository, FileRepository $fileRepository)
    {
        parent::__construct($repository);
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param UpdateArticle $command
     */
    public function __invoke(UpdateArticle $command): void
    {
        $article = $this->getArticle($command->getId());
        if ($article->isLegacyFormat()) {
            throw new \InvalidArgumentException('Legacy news articles cannot be edited');
        }

        $oldAttachments    = ArticleBody::findAttachments($article->getBody());
        $body              = $command->getBody();
        $newAttachments    = ArticleBody::findAttachments($body);
        $removeAttachments = array_diff($oldAttachments, $newAttachments);
        if (!empty($removeAttachments)) {
            $this->fileRepository->deleteById(...$removeAttachments);
        }

        $article->setSlug($this->findSuitableSlug(new \DateTimeImmutable(), $command->getTitle(), $article->getId()))
                ->setTitle($command->getTitle())
                ->setSubtitle($command->getSubtitle())
                ->setBody($body)
                ->setTags($command->getTags())
                ->setPublishedAt($command->getPublishedAt());
        $this->repository->save($article);
    }
}
