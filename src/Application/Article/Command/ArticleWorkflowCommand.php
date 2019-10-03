<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 13:20
 */

namespace App\Application\Article\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Article\Article;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ArticleWorkflowCommand
 *
 * @package App\Application\Article\Command
 */
abstract class ArticleWorkflowCommand
{
    use IdAware;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $transition;

    /**
     * @param Article $article
     * @param string  $transition
     * @return self
     */
    public static function create(Article $article, string $transition): self
    {
        switch ($transition) {
            case PublishArticle::TRANSITION:
                return new PublishArticle($article->getId());
            default:
                return new TransitionArticle($article->getId(), $transition);
        }
    }

    /**
     * @param string $id
     * @param string $transition
     */
    protected function __construct(string $id, string $transition)
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
}
