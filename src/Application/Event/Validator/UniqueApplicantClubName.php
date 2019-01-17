<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 11:39
 */

namespace App\Application\Event\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueApplicantClubName
 *
 * @package App\Application\Event\Validator
 *
 * @Annotation
 */
class UniqueApplicantClubName extends Constraint
{
    public $message = 'This club "{{ value }}" has already applied for event "{{ event }}".';
}
