<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:19
 */

namespace App\Application\User;

use Ramsey\Uuid\Uuid;

/**
 * Class CreateUser
 *
 * @package App\Application\User
 */
class CreateUser extends UserProperties
{
    use MutableUserCommand;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $confirmToken;

    /**
     * @param string|null $confirmToken
     * @return self
     */
    public static function create(?string $confirmToken = null): self
    {
        $id = Uuid::uuid4();
        return new self($id->toString(), $confirmToken ?? bin2hex(random_bytes(32)));
    }

    /**
     * @param string $id
     * @param string $confirmToken
     */
    private function __construct(string $id, string $confirmToken)
    {
        $this->id           = $id;
        $this->confirmToken = $confirmToken;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getConfirmToken(): string
    {
        return $this->confirmToken;
    }
}
