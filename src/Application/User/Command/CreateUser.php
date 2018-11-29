<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:19
 */

namespace App\Application\User\Command;

use Ramsey\Uuid\Uuid;

/**
 * Class CreateUser
 *
 * @package App\Application\User\Command
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
     * @return self
     */
    public static function create(): self
    {
        $id = Uuid::uuid4();
        return new self($id->toString(), bin2hex(random_bytes(32)));
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
