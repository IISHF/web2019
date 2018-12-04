<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-04
 * Time: 16:16
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Article\Article;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class WorkflowCommand
 *
 * @package App\Application\Article\Command
 */
class WorkflowCommand
{
    use UuidAware;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Choice(callback={"App\Domain\Model\Article\Article", "getAvailableTransitions"})
     *
     * @var string
     */
    private $transition;

    /**
     * @param Article $article
     * @param string  $transition
     * @return self
     */
    public static function transition(Article $article, string $transition): self
    {
        return new self($article->getId(), $transition);
    }

    /**
     * @param Article $article
     * @return self
     */
    public static function submit(Article $article): self
    {
        return new self($article->getId(), 'submit');
    }

    /**
     * @param Article $article
     * @return self
     */
    public static function reject(Article $article): self
    {
        return new self($article->getId(), 'reject');
    }

    /**
     * @param Article $article
     * @return self
     */
    public static function publish(Article $article): self
    {
        return new self($article->getId(), 'publish');
    }

    /**
     * @param string $id
     * @param string $transition
     */
    private function __construct(string $id, string $transition)
    {
        $this->id         = $id;
        $this->transition = $transition;
    }

    /**
     * @return string
     */
    public function getTransition(): string
    {
        return $this->transition;
    }

    /**
     * @param string $transition
     * @return $this
     */
    public function setTransition(string $transition): self
    {
        $this->transition = $transition;
        return $this;
    }
}
