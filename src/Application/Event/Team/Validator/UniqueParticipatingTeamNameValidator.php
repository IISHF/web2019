<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:26
 */

namespace App\Application\Event\Team\Validator;

use App\Application\Event\Team\Command\AddParticipatingTeam;
use App\Application\Event\Team\Command\UpdateParticipatingTeam;
use App\Domain\Model\Event\Team\ParticipatingTeamRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueParticipatingTeamNameValidator
 *
 * @package App\Application\Event\Team\Validator
 */
class UniqueParticipatingTeamNameValidator extends ConstraintValidator
{
    /**
     * @var ParticipatingTeamRepository
     */
    private $teamRepository;

    /**
     * @param ParticipatingTeamRepository $teamRepository
     */
    public function __construct(ParticipatingTeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueParticipatingTeamName) {
            throw new UnexpectedTypeException($constraint, UniqueParticipatingTeamName::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $object = $this->context->getObject();
        if ($object instanceof AddParticipatingTeam) {
            if ($tryTeam = $this->teamRepository->findByName($object->getEventId(), $value)) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ event }}', $tryTeam->getEvent()->getName())
                              ->addViolation();
            }
            return;
        }
        if ($object instanceof UpdateParticipatingTeam) {
            $team = $this->teamRepository->findById($object->getId());
            if (!$team) {
                return;
            }
            $tryTeam = $this->teamRepository->findByName($team->getEvent()->getId(), $value);
            if ($tryTeam && $team->getId() !== $tryTeam->getId()) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ event }}', $team->getEvent()->getName())
                              ->addViolation();
            }
            return;
        }

        throw new UnexpectedValueException(
            $object,
            implode(
                '|',
                [
                    AddParticipatingTeam::class,
                    UpdateParticipatingTeam::class,
                ]
            )
        );
    }
}
