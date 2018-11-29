<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 12:22
 */

namespace App\Application\User\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEmail
 *
 * @package App\Application\User\Validator
 *
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    public $message = 'This email "{{ value }}" is already used.';
}
