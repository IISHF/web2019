<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 14:47
 */

namespace App\Application\User\Command;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfirmUser
 *
 * @package App\Application\User\Command
 */
class ConfirmUser
{
    use PasswordAware, PasswordChangeOptions;

    /**
     * @var string
     */
    private $confirmToken;

    /**
     * @param string       $confirmToken
     * @param Request|null $request
     * @param string|null  $modifier
     * @return self
     */
    public static function confirm(string $confirmToken, ?Request $request = null, ?string $modifier = null): self
    {
        return new self($confirmToken, $request, $modifier);
    }

    /**
     * @param string       $confirmToken
     * @param Request|null $request
     * @param string|null  $modifier
     */
    private function __construct(string $confirmToken, ?Request $request, ?string $modifier)
    {
        $this->confirmToken = $confirmToken;
        $this->request      = $request;
        $this->modifier     = $modifier;
    }

    /**
     * @return string
     */
    public function getConfirmToken(): string
    {
        return $this->confirmToken;
    }
}
