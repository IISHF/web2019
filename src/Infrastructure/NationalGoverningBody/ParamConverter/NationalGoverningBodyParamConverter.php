<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 11:13
 */

namespace App\Infrastructure\NationalGoverningBody\ParamConverter;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class NationalGoverningBodyParamConverter
 *
 * @package App\Infrastructure\NationalGoverningBody\ParamConverter
 */
class NationalGoverningBodyParamConverter extends LoaderParamConverter
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
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === NationalGoverningBody::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        if (Uuid::isValid($value)) {
            return $this->ngbRepository->findById($value);
        }
        return $this->ngbRepository->findBySlug($value);
    }
}
