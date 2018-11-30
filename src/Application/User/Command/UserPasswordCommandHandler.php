<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 09:07
 */

namespace App\Application\User\Command;

use App\Application\User\PasswordService;
use App\Domain\Model\User\UserRepository;

/**
 * Class UserPasswordCommandHandler
 *
 * @package App\Application\User\Command
 */
abstract class UserPasswordCommandHandler extends UserCommandHandler
{
    /**
     * @var PasswordService
     */
    private $passwordService;

    /**
     * @param UserRepository  $repository
     * @param PasswordService $passwordService
     */
    public function __construct(UserRepository $repository, PasswordService $passwordService)
    {
        parent::__construct($repository);
        $this->passwordService = $passwordService;
    }

    /**
     * @param string $password
     * @return string
     */
    protected function encodePassword(string $password): string
    {
        return $this->passwordService->encodePassword($password);
    }
}
