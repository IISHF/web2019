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
    private $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
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
        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $tryUser = $this->repository->findByEmail($value);
        if (!$tryUser) {
            return;
        }

        $root = $this->context->getRoot();
        if ($root instanceof UpdateUser && $root->getUser()->getId() === $tryUser->getId()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
