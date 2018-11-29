<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 17:20
 */

namespace App\Infrastructure\User\ViewModel;

use App\Domain\Model\User\User;

/**
 * Class ListItem
 *
 * @package App\Infrastructure\User\ViewModel
 */
class ListItem implements \JsonSerializable
{
    /**
     * @var User
     */
    private $user;

    /**
     * @param User $user
     * @return self
     */
    public static function wrap(User $user): self
    {
        return new self($user);
    }

    /**
     * @param User $user
     */
    private function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'id'         => $this->user->getId(),
            'first_name' => $this->user->getFirstName(),
            'last_name'  => $this->user->getLastName(),
            'name'       => $this->user->getName(),
            'email'      => $this->user->getEmail(),
        ];
    }
}
