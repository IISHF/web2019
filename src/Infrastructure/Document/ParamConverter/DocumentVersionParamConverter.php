<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:05
 */

namespace App\Infrastructure\Document\ParamConverter;

use App\Domain\Model\Document\DocumentRepository;
use App\Domain\Model\Document\DocumentVersion;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DocumentVersionParamConverter
 *
 * @package App\Infrastructure\Document\ParamConverter
 */
class DocumentVersionParamConverter extends LoaderParamConverter
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
        return $configuration->getClass() === DocumentVersion::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        if (Uuid::isValid($value)) {
            return $this->documentRepository->findVersionById($value);
        }

        $documentParam = $configuration->getOptions()['document'] ?? 'document';
        if (!$request->attributes->has($documentParam)) {
            if ($configuration->isOptional()) {
                return null;
            }
            throw new BadRequestHttpException(
                sprintf(
                    'Document parameter %s not found for class %s.',
                    $documentParam,
                    $configuration->getClass()
                )
            );
        }
        $documentValue = $request->attributes->get($documentParam);
        if (!$documentValue) {
            if ($configuration->isOptional()) {
                return null;
            }
            throw new BadRequestHttpException(
                sprintf(
                    'Value not found for document parameter %s for class %s.',
                    $documentParam,
                    $configuration->getClass()
                )
            );
        }

        $version = $this->documentRepository->findVersionBySlug($documentValue, $value);
        if (!$version && !$configuration->isOptional()) {
            throw new NotFoundHttpException(
                sprintf(
                    'Document version not found for version parameter %s with value %s and document parameter %s with value %s.',
                    $configuration->getName(),
                    $value,
                    $documentParam,
                    $documentValue
                )
            );
        }
        return $version;
    }
}
