<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:13
 */

namespace App\Domain\Model\User;

/**
 * @package App\Domain\Model\User
 */
interface UserInterface
{
    /**
     * @return string
     */
    public function getId(): string;
}
