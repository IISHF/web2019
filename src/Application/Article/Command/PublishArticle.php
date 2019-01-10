<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 12:28
 */

namespace App\Application\Article\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PublishArticle
 *
 * @package App\Application\Article\Command
 */
class PublishArticle extends ArticleWorkflowCommand
{
    public const TRANSITION = 'publish';

    /**
     * @Assert\Type("\DateTimeImmutable")
     * @Assert\GreaterThan("now")
     * @Assert\NotNull()
     *
     * @var \DateTimeImmutable
     */
    private $publishAt;

    /**
     * @param string $id
     */
    protected function __construct(string $id)
    {
        parent::__construct($id, self::TRANSITION);
        $this->publishAt = new \DateTimeImmutable('+ 15 minutes');
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getPublishAt(): \DateTimeImmutable
    {
        return $this->publishAt;
    }

    /**
     * @param \DateTimeImmutable $publishAt
     * @return $this
     */
    public function setPublishAt(\DateTimeImmutable $publishAt): self
    {
        $this->publishAt = $publishAt;
        return $this;
    }
}
