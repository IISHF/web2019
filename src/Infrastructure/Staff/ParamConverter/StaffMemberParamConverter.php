<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:45
 */

namespace App\Infrastructure\Staff\ParamConverter;

use App\Domain\Model\Staff\StaffMember;
use App\Domain\Model\Staff\StaffMemberRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class StaffMemberParamConverter
 *
 * @package App\Infrastructure\Staff\ParamConverter
 */
class StaffMemberParamConverter extends LoaderParamConverter
{
    /**
     * @var StaffMemberRepository
     */
    private $memberRepository;

    /**
     * @param StaffMemberRepository $memberRepository
     */
    public function __construct(StaffMemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === StaffMember::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        return $this->memberRepository->findById($value);
    }
}
