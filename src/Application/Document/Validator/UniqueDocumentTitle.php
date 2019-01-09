<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 12:13
 */

namespace App\Application\Document\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueDocumentTitle
 *
 * @package App\Application\Document\Validator
 *
 * @Annotation
 */
class UniqueDocumentTitle extends Constraint
{
    public $message = 'This title "{{ value }}" is already used.';
}
