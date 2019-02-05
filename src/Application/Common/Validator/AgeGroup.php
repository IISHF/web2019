<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:02
 */

namespace App\Application\Common\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class AgeGroup
 *
 * @package App\Application\Common\Validator
 *
 * @Annotation
 */
class AgeGroup extends Constraint
{
    public $message = 'The age group "{{ value }}" is not not valid.';
}
