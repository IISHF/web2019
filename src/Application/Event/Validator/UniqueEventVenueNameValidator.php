<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-15
 * Time: 07:26
 */

namespace App\Application\Event\Validator;

use App\Application\Event\Command\UpdateEventVenue;
use App\Domain\Model\Event\EventVenueRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueEventVenueNameValidator
 *
 * @package App\Application\Event\Validator
 */
class UniqueEventVenueNameValidator extends ConstraintValidator
{
    /**
     * @var EventVenueRepository
     */
    private $venueRepository;

    /**
     * @param EventVenueRepository $venueRepository
     */
    public function __construct(EventVenueRepository $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEventVenueName) {
            throw new UnexpectedTypeException($constraint, UniqueEventVenueName::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $tryVenue = $this->venueRepository->findByName($value);
        if (!$tryVenue) {
            return;
        }

        $object = $this->context->getObject();
        if ($object instanceof UpdateEventVenue && $object->getId() === $tryVenue->getId()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
