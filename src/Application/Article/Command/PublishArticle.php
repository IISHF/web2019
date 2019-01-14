<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 12:28
 */

namespace App\Application\Article\Command;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class PublishArticle
 *
 * @package App\Application\Article\Command
 */
class PublishArticle extends ArticleWorkflowCommand
{
    public const TRANSITION = 'publish';

    /**
     * @Assert\Type("bool")
     * @Assert\NotNull()
     *
     * @var bool
     */
    private $publishNow = true;

    /**
     * @Assert\Type("DateTimeImmutable")
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
     * @return bool
     */
    public function isPublishNow(): bool
    {
        return $this->publishNow;
    }

    /**
     * @param bool $publishNow
     * @return $this
     */
    public function setPublishNow(bool $publishNow): self
    {
        $this->publishNow = $publishNow;
        return $this;
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

    /**
     * @Assert\Callback()
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload): void
    {
        if ($this->publishNow === false) {
            $now = new \DateTimeImmutable('now');
            if ($this->publishAt < $now) {
                $context->buildViolation('This value should not be in the past.')
                        ->atPath('publishAt')
                        ->addViolation();
            }

        }
    }
}
