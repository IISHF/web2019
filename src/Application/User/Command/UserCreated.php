<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 15:30
 */

namespace App\Application\User\Command;

use App\Domain\Model\User\User;

/**
 * Class UserCreated
 *
 * @package App\Application\User\Command
 */
class UserCreated
{
    use UserAware;

    /**
     * @var string
     */
    private $confirmToken;

    /**
     * @param User   $user
     * @param string $confirmToken
     * @return self
     */
    public static function created(User $user, string $confirmToken): self
    {
        return new self($user, $confirmToken);
    }

    /**
     * @param User   $user
     * @param string $confirmToken
     */
    private function __construct(User $user, string $confirmToken)
    {
        $this->user         = $user;
        $this->confirmToken = $confirmToken;
    }

    /**
     * @return string
     */
    public function getConfirmToken(): string
    {
        return $this->confirmToken;
    }
}
