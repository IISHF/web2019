<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:59
 */

namespace App\Application\Article\Validator;

use App\Application\Article\Command\UpdateArticle;
use App\Domain\Model\Article\ArticleRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class UniqueSlugValidator
 *
 * @package App\Application\Article\Validator
 */
class UniqueSlugValidator extends ConstraintValidator
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @param ArticleRepository $repository
     */
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueSlug) {
            throw new UnexpectedTypeException($constraint, UniqueSlug::class);
        }
        if ($value === null || $value === '') {
            return;
        }
        if (!\is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $tryArticle = $this->repository->findBySlug($value);
        if (!$tryArticle) {
            return;
        }

        $root = $this->context->getRoot();
        if ($root instanceof UpdateArticle && $root->getArticle()->getId() === $tryArticle->getId()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
                      ->setParameter('{{ value }}', $value)
                      ->addViolation();
    }
}
