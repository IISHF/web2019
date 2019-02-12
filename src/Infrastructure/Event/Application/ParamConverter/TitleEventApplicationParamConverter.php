<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 11:12
 */

namespace App\Infrastructure\Event\Application\ParamConverter;

use App\Domain\Model\Event\Application\TitleEventApplication;
use App\Domain\Model\Event\Application\TitleEventApplicationRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TitleEventApplicationParamConverter
 *
 * @package App\Infrastructure\Event\Application\ParamConverter
 */
class TitleEventApplicationParamConverter extends LoaderParamConverter
{
    /**
     * @var TitleEventApplicationRepository
     */
    private $applicationRepository;

    /**
     * @param TitleEventApplicationRepository $applicationRepository
     */
    public function __construct(TitleEventApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === TitleEventApplication::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        return $this->applicationRepository->findById($value);
    }
}
