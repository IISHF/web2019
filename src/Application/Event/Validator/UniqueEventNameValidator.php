<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:25
 */

namespace App\Application\Event\Validator;

use App\Application\Event\Command\EuropeanChampionship\CreateEuropeanChampionship;
use App\Application\Event\Command\EuropeanChampionship\UpdateAnnouncedEuropeanChampionship;
use App\Application\Event\Command\EuropeanChampionship\UpdateEuropeanChampionship;
use App\Application\Event\Command\EuropeanChampionship\UpdateSanctionedEuropeanChampionship;
use App\Application\Event\Command\EuropeanCup\CreateEuropeanCup;
use App\Application\Event\Command\EuropeanCup\UpdateAnnouncedEuropeanCup;
use App\Application\Event\Command\EuropeanCup\UpdateEuropeanCup;
use App\Application\Event\Command\EuropeanCup\UpdateSanctionedEuropeanCup;
use App\Application\Event\Command\Tournament\CreateTournament;
use App\Application\Event\Command\Tournament\UpdateSanctionedTournament;
use App\Application\Event\Command\Tournament\UpdateTournament;
use App\Domain\Model\Event\EventRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use function is_string;

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
        if (!is_string($value)) {
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
            || $object instanceof UpdateAnnouncedEuropeanChampionship
            || $object instanceof UpdateSanctionedEuropeanChampionship
            || $object instanceof UpdateEuropeanCup
            || $object instanceof UpdateAnnouncedEuropeanCup
            || $object instanceof UpdateSanctionedEuropeanCup
            || $object instanceof UpdateTournament
            || $object instanceof UpdateSanctionedTournament
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
                    UpdateAnnouncedEuropeanChampionship::class,
                    UpdateSanctionedEuropeanChampionship::class,
                    UpdateEuropeanCup::class,
                    UpdateAnnouncedEuropeanCup::class,
                    UpdateSanctionedEuropeanCup::class,
                    UpdateTournament::class,
                    UpdateSanctionedTournament::class,
                ]
            )
        );
    }
}
