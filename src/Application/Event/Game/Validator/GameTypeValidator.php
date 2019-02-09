<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:04
 */

namespace App\Application\Event\Game\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class GameTypeValidator
 *
 * @package App\Application\Event\Game\Validator
 */
class GameTypeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof GameType) {
            throw new UnexpectedTypeException($constraint, GameType::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_int($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }

        if (\App\Domain\Model\Event\Game\GameType::isValidGameType($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
