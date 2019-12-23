<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:25
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use App\Domain\Model\File\FileRepository;
use DateTimeImmutable;
use InvalidArgumentException;

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
     * @param ArticleRepository $articleRepository
     * @param FileRepository    $fileRepository
     */
    public function __construct(ArticleRepository $articleRepository, FileRepository $fileRepository)
    {
        parent::__construct($articleRepository);
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param UpdateArticle $command
     */
    public function __invoke(UpdateArticle $command): void
    {
        $article = $this->getArticle($command->getId());
        if ($article->isLegacyFormat()) {
            throw new InvalidArgumentException('Legacy news articles cannot be edited');
        }

        $oldAttachments    = $article->getAttachments(false);
        $body              = $command->getBody();
        $newAttachments    = Article::findAttachments($body, false);
        $removeAttachments = array_diff($oldAttachments, $newAttachments);
        if (!empty($removeAttachments)) {
            $this->fileRepository->deleteById(...$removeAttachments);
        }

        if ($article->isPublished()) {
            $publishedAt = $command->getPublishedAt();
        } else {
            $publishedAt = null;
        }

        $slug = $this->findSuitableSlug(
            $publishedAt ?? new DateTimeImmutable('now'),
            $command->getTitle(),
            $article->getId()
        );

        $article->setSlug($slug)
                ->setTitle($command->getTitle())
                ->setSubtitle($command->getSubtitle())
                ->setBody($body)
                ->setTags($command->getTags());

        if ($publishedAt !== null) {
            $article->setPublishedAt($publishedAt);
        }

        $this->articleRepository->save($article);
    }
}
