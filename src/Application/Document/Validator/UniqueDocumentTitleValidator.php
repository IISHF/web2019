<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 12:14
 */

namespace App\Application\Document\Validator;

use App\Application\Document\Command\UpdateDocument;
use App\Domain\Model\Document\DocumentRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueDocumentTitleValidator
 *
 * @package App\Application\Document\Validator
 */
class UniqueDocumentTitleValidator extends ConstraintValidator
{
    /**
     * @var DocumentRepository
     */
    private $repository;

    /**
     * @param DocumentRepository $repository
     */
    public function __construct(DocumentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueDocumentTitle) {
            throw new UnexpectedTypeException($constraint, UniqueDocumentTitle::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $tryDocument = $this->repository->findByTitle($value);
        if (!$tryDocument) {
            return;
        }

        $object = $this->context->getObject();
        if ($object instanceof UpdateDocument && $object->getId() === $tryDocument->getId()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
