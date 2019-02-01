<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:52
 */

namespace App\Infrastructure\Committee\ParamConverter;

use App\Domain\Model\Committee\Committee;
use App\Domain\Model\Committee\CommitteeRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommitteeParamConverter
 *
 * @package App\Infrastructure\Committee\ParamConverter
 */
class CommitteeParamConverter extends LoaderParamConverter
{
    /**
     * @var CommitteeRepository
     */
    private $committeeRepository;

    /**
     * @param CommitteeRepository $committeeRepository
     */
    public function __construct(CommitteeRepository $committeeRepository)
    {
        $this->committeeRepository = $committeeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === Committee::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        if ($configuration->getOptions()['with_members'] ?? false) {
            if (Uuid::isValid($value)) {
                return $this->committeeRepository->findByIdWithMembers($value);
            }
            return $this->committeeRepository->findBySlugWithMembers($value);
        }

        if (Uuid::isValid($value)) {
            return $this->committeeRepository->findById($value);
        }
        return $this->committeeRepository->findBySlug($value);
    }
}
