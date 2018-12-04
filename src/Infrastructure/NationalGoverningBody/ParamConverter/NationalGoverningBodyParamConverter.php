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
    private $repository;

    /**
     * @param NationalGoverningBodyRepository $repository
     */
    public function __construct(NationalGoverningBodyRepository $repository)
    {
        $this->repository = $repository;
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
    protected function loadObject($value, ParamConverter $configuration): ?object
    {
        if (Uuid::isValid($value)) {
            return $this->repository->findById($value);
        }
        return $this->repository->findBySlug($value);
    }
}
