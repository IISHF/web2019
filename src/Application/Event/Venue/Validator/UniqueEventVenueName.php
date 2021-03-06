<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:23
 */

namespace App\Application\Event\Venue\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEventVenueName
 *
 * @package App\Application\Event\Venue\Validator
 *
 * @Annotation
 */
class UniqueEventVenueName extends Constraint
{
    public $message = 'This name "{{ value }}" is already used.';
}
