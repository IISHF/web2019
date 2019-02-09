<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:03
 */

namespace App\Application\Event\Game\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class GameType
 *
 * @package App\Application\Event\Game\Validator
 *
 * @Annotation
 */
class GameType extends Constraint
{
    public $message = 'The game type "{{ value }}" is not not valid.';
}
