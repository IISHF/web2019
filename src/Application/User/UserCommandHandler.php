<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:26
 */

namespace App\Application\User;

use App\Domain\Model\User\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class UserCommandHandler
 *
 * @package App\Application\User
 */
abstract class UserCommandHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
}
