<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:58
 */

namespace App\Application\Article\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueSlug
 *
 * @package App\Application\Article\Validator
 *
 * @Annotation
 */
class UniqueSlug extends Constraint
{
    public $message = 'This slug "{{ value }}" is already used.';
}
