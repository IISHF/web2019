<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-06
 * Time: 09:32
 */

namespace App\Application\Committee\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class TermType
 *
 * @package App\Application\Committee\Validator
 *
 * @Annotation
 */
class TermType extends Constraint
{
    public $message = 'The term type "{{ value }}" is not not valid.';
}
