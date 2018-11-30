<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:25
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\ArticleRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class ArticleCommandHandler
 *
 * @package App\Application\Article\Command
 */
abstract class ArticleCommandHandler implements MessageHandlerInterface
{
    /**
     * @var ArticleRepository
     */
    protected $repository;

    /**
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }
}
