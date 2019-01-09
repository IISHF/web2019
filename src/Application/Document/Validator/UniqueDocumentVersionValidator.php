<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 12:16
 */

namespace App\Application\Document\Validator;

use App\Application\Document\Command\CreateDocument;
use App\Application\Document\Command\CreateDocumentVersion;
use App\Application\Document\Command\UpdateDocumentVersion;
use App\Domain\Model\Document\DocumentRepository;
use App\Infrastructure\Document\Form\UpdateDocumentVersionType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class UniqueDocumentVersionValidator
 *
 * @package App\Application\Document\Validator
 */
class UniqueDocumentVersionValidator extends ConstraintValidator
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
        if (!$constraint instanceof UniqueDocumentVersion) {
            throw new UnexpectedTypeException($constraint, UniqueDocumentVersion::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $object = $this->context->getObject();
        if ($object instanceof CreateDocument) {
            if ($tryVersion = $this->repository->findVersion($object->getId(), $value)) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ document }}', $tryVersion->getDocument()->getTitle())
                              ->addViolation();
            }
            return;
        }
        if ($object instanceof CreateDocumentVersion) {
            if ($tryVersion = $this->repository->findVersion($object->getDocumentId(), $value)) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ document }}', $tryVersion->getDocument()->getTitle())
                              ->addViolation();
            }
            return;
        }
        if ($object instanceof UpdateDocumentVersion) {
            $version = $this->repository->findVersionById($object->getId());
            if (!$version) {
                return;
            }
            $tryVersion = $this->repository->findVersion($version->getDocument()->getId(), $value);
            if ($tryVersion && $version->getId() !== $tryVersion->getId()) {
                $this->context->buildViolation($constraint->message)
                              ->setParameter('{{ value }}', $value)
                              ->setParameter('{{ document }}', $version->getDocument()->getTitle())
                              ->addViolation();
            }
            return;
        }

        throw new UnexpectedValueException(
            $object,
            implode(
                '|',
                [
                    CreateDocument::class,
                    CreateDocumentVersion::class,
                    UpdateDocumentVersionType::class,
                ]
            )
        );
    }
}
