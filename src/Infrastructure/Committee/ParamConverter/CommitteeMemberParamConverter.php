<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:44
 */

namespace App\Infrastructure\Committee\ParamConverter;

use App\Domain\Model\Committee\CommitteeMember;
use App\Domain\Model\Committee\CommitteeRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommitteeMemberParamConverter
 *
 * @package App\Infrastructure\Committee\ParamConverter
 */
class CommitteeMemberParamConverter extends LoaderParamConverter
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
        return $configuration->getClass() === CommitteeMember::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        return $this->committeeRepository->findMemberById($value);
    }
}
