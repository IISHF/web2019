<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 13:27
 */

namespace App\Application\NationalGoverningBody\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueNationalGoverningBody
 *
 * @package App\Application\NationalGoverningBody\Validator
 *
 * @Annotation
 */
class UniqueNationalGoverningBody extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

}
