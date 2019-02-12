<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 11:39
 */

namespace App\Application\Event\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueApplicantClubName
 *
 * @package App\Application\Event\Application\Validator
 *
 * @Annotation
 */
class UniqueApplicantClubName extends Constraint
{
    public $message = 'This club "{{ value }}" has already applied for event "{{ event }}".';
}
