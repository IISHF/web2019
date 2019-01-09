<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:19
 */

namespace App\Application\User\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateUser
 *
 * @package App\Application\User\Command
 */
class CreateUser
{
    use UuidAware, UserProperties, MutableUser;

    /**
     * @var string
     */
    private $confirmToken;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid(), bin2hex(random_bytes(32)));
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
    public function getConfirmToken(): string
    {
        return $this->confirmToken;
    }
}
