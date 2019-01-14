<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 13:27
 */

namespace App\Application\NationalGoverningBody\Validator;

use App\Application\NationalGoverningBody\Command\IdentifiesNationalGoverningBody;
use App\Application\NationalGoverningBody\Command\UpdateNationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueNationalGoverningBodyValidator
 *
 * @package App\Application\NationalGoverningBody\Validator
 */
class UniqueNationalGoverningBodyValidator extends ConstraintValidator
{
    /**
     * @var NationalGoverningBodyRepository
     */
    private $ngbRepository;

    /**
     * @param NationalGoverningBodyRepository $ngbRepository
     */
    public function __construct(NationalGoverningBodyRepository $ngbRepository)
    {
        $this->ngbRepository = $ngbRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueNationalGoverningBody) {
            throw new UnexpectedTypeException($constraint, UniqueNationalGoverningBody::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!$value instanceof IdentifiesNationalGoverningBody) {
            throw new UnexpectedValueException($value, IdentifiesNationalGoverningBody::class);
        }

        $this->addViolationIf(
            $value,
            $this->ngbRepository->findByName($value->getName()),
            'The name {{ value }} is already used.',
            $value->getName(),
            'name'
        );
        $this->addViolationIf(
            $value,
            $this->ngbRepository->findByAcronym($value->getAcronym()),
            'The acronym {{ value }} is already used.',
            $value->getAcronym(),
            'acronym'
        );
        $this->addViolationIf(
            $value,
            $this->ngbRepository->findByIocCode($value->getIocCode()),
            'The IOC code {{ value }} is already used.',
            $value->getIocCode(),
            'iocCode'
        );
        $this->addViolationIf(
            $value,
            $this->ngbRepository->findByEmail($value->getEmail()),
            'The email {{ value }} is already used.',
            $value->getEmail(),
            'email'
        );
    }

    /**
     * @param IdentifiesNationalGoverningBody $properties
     * @param NationalGoverningBody|null      $ngb
     * @param string                          $message
     * @param mixed                           $value
     * @param string                          $path
     * @param string                          $param
     */
    private function addViolationIf(
        IdentifiesNationalGoverningBody $properties,
        ?NationalGoverningBody $ngb,
        string $message,
        $value,
        string $path,
        string $param = '{{ value }}'
    ): void {
        if (!$ngb) {
            return;
        }
        if ($properties instanceof UpdateNationalGoverningBody && $properties->getId() === $ngb->getId()) {
            return;
        }

        $this->context->buildViolation($message)
                      ->setParameter($param, $value)
                      ->atPath($path)
                      ->addViolation();
    }
}
