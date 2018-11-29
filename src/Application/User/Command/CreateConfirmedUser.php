<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 09:29
 */

namespace App\Application\User\Command;

use Ramsey\Uuid\Uuid;

/**
 * Class CreateConfirmedUser
 *
 * @package App\Application\User\Command
 */
class CreateConfirmedUser extends UserProperties
{
    use PasswordAware, MutableUserCommand;

    /**
     * @var string
     */
    private $id;

    /**
     * @return self
     */
    public static function create(): self
    {
        $id = Uuid::uuid4();
        return new self($id->toString());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
