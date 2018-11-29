<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 09:00
 */

namespace App\Application\User\Command;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class SetPassword
 *
 * @package App\Application\User\Command
 */
class SetPassword
{
    use PasswordAware, PasswordChangeOptions;

    /**
     * @var string
     */
    private $email;

    /**
     * @param string       $email
     * @param Request|null $request
     * @param string|null  $modifier
     * @return self
     */
    public static function set(string $email, ?Request $request = null, ?string $modifier = null): self
    {
        return new self($email, $request, $modifier);
    }

    /**
     * @param string       $email
     * @param Request|null $request
     * @param string|null  $modifier
     */
    private function __construct(string $email, ?Request $request, ?string $modifier)
    {
        $this->email    = $email;
        $this->request  = $request;
        $this->modifier = $modifier;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
