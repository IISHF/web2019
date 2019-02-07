<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-07
 * Time: 07:38
 */

namespace App\Application\Committee\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class MemberType
 *
 * @package App\Application\Committee\Validator
 *
 * @Annotation
 */
class MemberType extends Constraint
{
    public $message = 'The member type "{{ value }}" is not not valid.';
}
