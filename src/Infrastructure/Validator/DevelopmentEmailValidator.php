<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 11:30
 */

namespace App\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class DevelopmentEmailValidator
 *
 * @package App\Infrastructure\Validator
 */
class DevelopmentEmailValidator implements ConstraintValidatorInterface
{
    /**
     * @var EmailValidator
     */
    private $inner;

    /**
     * @param EmailValidator $inner
     */
    public function __construct(EmailValidator $inner)
    {
        $this->inner = $inner;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExecutionContextInterface $context): void
    {
        $this->inner->initialize($context);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Email) {
            throw new UnexpectedTypeException($constraint, Email::class);
        }

        // disable network checks in development mode - allow using offline conditions
        /** @var Email $constraint */
        $constraint->checkHost = false;
        $constraint->checkMX   = false;

        $this->inner->validate($value, $constraint);
    }
}
