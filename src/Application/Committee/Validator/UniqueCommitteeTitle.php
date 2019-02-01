<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:47
 */

namespace App\Application\Committee\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueCommitteeTitle
 *
 * @package App\Application\Committee\Validator
 *
 * @Annotation
 */
class UniqueCommitteeTitle extends Constraint
{
    public $message = 'This title "{{ value }}" is already used.';
}
