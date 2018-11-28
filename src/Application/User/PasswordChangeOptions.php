<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 13:07
 */

namespace App\Application\User;

use Symfony\Component\HttpFoundation\Request;

/**
 * Trait PasswordCommand
 *
 * @package App\Application\User
 */
trait PasswordChangeOptions
{
    /**
     * @var Request|null
     */
    private $request;

    /**
     * @var string|null
     */
    private $modifier;

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        return $this->request;
    }

    /**
     * @return string|null
     */
    public function getModifier(): ?string
    {
        return $this->modifier;
    }
}

