<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 11:40
 */

namespace App\Application\Event\Application\Validator;

use App\Application\Event\Application\Command\ApplyForTitleEvent;
use App\Application\Event\Application\Command\UpdateTitleEventApplication;
use App\Domain\Model\Event\Application\TitleEventApplicationRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function is_string;

/**
 * Class UniqueApplicantClubNameValidator
 *
 * @package App\Application\Event\Validator
 */
class UniqueApplicantClubNameValidator extends ConstraintValidator
{
    /**
     * @var TitleEventApplicationRepository
     */
    private $applicationRepository;

    /**
     * @param TitleEventApplicationRepository $applicationRepository
     */
    public function __construct(TitleEventApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueApplicantClubName) {
            throw new UnexpectedTypeException($constraint, UniqueApplicantClubName::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $object = $this->context->getObject();
        if ($object instanceof ApplyForTitleEvent) {
            if ($tryApplication = $this->applicationRepository->findByClub($object->getEventId(), $value)) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ event }}', $tryApplication->getTitleEvent()->getName())
                              ->addViolation();
            }
            return;
        }
        if ($object instanceof UpdateTitleEventApplication) {
            $application = $this->applicationRepository->findById($object->getId());
            if (!$application) {
                return;
            }
            $tryApplication = $this->applicationRepository->findByClub($application->getTitleEvent()->getId(), $value);
            if ($tryApplication && $application->getId() !== $tryApplication->getId()) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ event }}', $tryApplication->getTitleEvent()->getName())
                              ->addViolation();
            }
            return;
        }

        throw new UnexpectedValueException(
            $object,
            implode(
                '|',
                [
                    ApplyForTitleEvent::class,
                    UpdateTitleEventApplication::class,
                ]
            )
        );
    }
}
