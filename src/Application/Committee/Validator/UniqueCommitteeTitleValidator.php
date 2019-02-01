<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:47
 */

namespace App\Application\Committee\Validator;

use App\Application\Committee\Command\UpdateCommittee;
use App\Domain\Model\Committee\CommitteeRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueCommitteeTitleValidator
 *
 * @package App\Application\Committee\Validator
 */
class UniqueCommitteeTitleValidator extends ConstraintValidator
{
    /**
     * @var CommitteeRepository
     */
    private $committeeRepository;

    /**
     * @param CommitteeRepository $committeeRepository
     */
    public function __construct(CommitteeRepository $committeeRepository)
    {
        $this->committeeRepository = $committeeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueCommitteeTitle) {
            throw new UnexpectedTypeException($constraint, UniqueCommitteeTitle::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $tryDocument = $this->committeeRepository->findByTitle($value);
        if (!$tryDocument) {
            return;
        }

        $object = $this->context->getObject();
        if ($object instanceof UpdateCommittee && $object->getId() === $tryDocument->getId()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
