<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 12:23
 */

namespace App\Application\User\Validator;

use App\Application\User\Command\UpdateUser;
use App\Domain\Model\User\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function is_string;

/**
 * Class UniqueEmailValidator
 *
 * @package App\Application\User\Validator
 */
class UniqueEmailValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEmail) {
            throw new UnexpectedTypeException($constraint, UniqueEmail::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $tryUser = $this->userRepository->findByEmail($value);
        if (!$tryUser) {
            return;
        }

        $object = $this->context->getObject();
        if ($object instanceof UpdateUser && $object->getId() === $tryUser->getId()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
