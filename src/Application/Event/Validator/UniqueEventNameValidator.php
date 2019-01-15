<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:25
 */

namespace App\Application\Event\Validator;

use App\Application\Event\Command\CreateEuropeanChampionship;
use App\Application\Event\Command\CreateEuropeanCup;
use App\Application\Event\Command\CreateTournament;
use App\Application\Event\Command\UpdateEuropeanChampionship;
use App\Application\Event\Command\UpdateEuropeanCup;
use App\Application\Event\Command\UpdateTournament;
use App\Domain\Model\Event\EventRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueEventNameValidator
 *
 * @package App\Application\Event\Validator
 */
class UniqueEventNameValidator extends ConstraintValidator
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEventName) {
            throw new UnexpectedTypeException($constraint, UniqueEventName::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $object = $this->context->getObject();
        if ($object instanceof CreateEuropeanChampionship
            || $object instanceof CreateEuropeanCup
            || $object instanceof CreateTournament
        ) {
            if ($tryEvent = $this->eventRepository->findByName($object->getSeason(), $value)) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ season }}', $tryEvent->getSeason())
                              ->addViolation();
            }
            return;
        }
        if ($object instanceof UpdateEuropeanChampionship
            || $object instanceof UpdateEuropeanCup
            || $object instanceof UpdateTournament
        ) {
            $event = $this->eventRepository->findById($object->getId());
            if (!$event) {
                return;
            }
            $tryEvent = $this->eventRepository->findByName($event->getSeason(), $value);
            if ($tryEvent && $event->getId() !== $tryEvent->getId()) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ season }}', $event->getSeason())
                              ->addViolation();
            }
            return;
        }

        throw new UnexpectedValueException(
            $object,
            implode(
                '|',
                [
                    CreateEuropeanChampionship::class,
                    CreateEuropeanCup::class,
                    CreateTournament::class,
                    UpdateEuropeanChampionship::class,
                    UpdateEuropeanCup::class,
                    UpdateTournament::class,
                ]
            )
        );
    }
}
