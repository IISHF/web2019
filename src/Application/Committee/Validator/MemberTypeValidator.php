<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-06
 * Time: 09:33
 */

namespace App\Application\Committee\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function is_int;

/**
 * Class MemberTypeValidator
 *
 * @package App\Application\Committee\Validator
 */
class MemberTypeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof MemberType) {
            throw new UnexpectedTypeException($constraint, MemberType::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!is_int($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }

        if (\App\Domain\Model\Committee\MemberType::isValidMemberType($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
