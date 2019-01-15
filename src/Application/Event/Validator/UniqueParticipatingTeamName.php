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
 * Class UniqueParticipatingTeamName
 *
 * @package App\Application\Event\Validator
 */
class UniqueParticipatingTeamName extends Constraint
{
    public $message = 'This team "{{ value }}" is already registered for event "{{ event }}".';
}
