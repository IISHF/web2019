<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:03
 */

namespace App\Infrastructure\Document\ParamConverter;

use App\Domain\Model\Document\Document;
use App\Domain\Model\Document\DocumentRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DocumentParamConverter
 *
 * @package App\Infrastructure\Document\ParamConverter
 */
class DocumentParamConverter extends LoaderParamConverter
{
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * @param DocumentRepository $documentRepository
     */
    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === Document::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        if ($configuration->getOptions()['with_versions'] ?? false) {
            if (Uuid::isValid($value)) {
                return $this->documentRepository->findByIdWithVersions($value);
            }
            return $this->documentRepository->findBySlugWithVersions($value);
        }

        if (Uuid::isValid($value)) {
            return $this->documentRepository->findById($value);
        }
        return $this->documentRepository->findBySlug($value);
    }
}
