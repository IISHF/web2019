<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-06
 * Time: 09:33
 */

namespace App\Application\Committee\Validator;

use App\Domain\Model\Committee\TermType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class TermTypeValidator
 *
 * @package App\Application\Committee\Validator
 */
class TermTypeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof TermType) {
            throw new UnexpectedTypeException($constraint, TermType::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_int($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }

        if (TermType::isValidTermType($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
