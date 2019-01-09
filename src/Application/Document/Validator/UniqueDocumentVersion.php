<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 12:15
 */

namespace App\Application\Document\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueDocumentVersion
 *
 * @package App\Application\Document\Validator
 *
 * @Annotation
 */
class UniqueDocumentVersion extends Constraint
{
    public $message = 'This version "{{ value }}" is already used for document "{{ document }}".';
}
