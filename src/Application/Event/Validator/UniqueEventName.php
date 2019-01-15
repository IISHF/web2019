<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:24
 */

namespace App\Application\Event\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEventName
 *
 * @package App\Application\Event\Validator
 *
 * @Annotation
 */
class UniqueEventName extends Constraint
{
    public $message = 'This event name "{{ value }}" is already used in season "{{ season }}".';
}
