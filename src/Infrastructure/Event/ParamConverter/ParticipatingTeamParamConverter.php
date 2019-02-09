<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 11:12
 */

namespace App\Infrastructure\Event\ParamConverter;

use App\Domain\Model\Event\ParticipatingTeam;
use App\Domain\Model\Event\ParticipatingTeamRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ParticipatingTeamParamConverter
 *
 * @package App\Infrastructure\Event\ParamConverter
 */
class ParticipatingTeamParamConverter extends LoaderParamConverter
{
    /**
     * @var ParticipatingTeamRepository
     */
    private $teamRepository;

    /**
     * @param ParticipatingTeamRepository $teamRepository
     */
    public function __construct(ParticipatingTeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === ParticipatingTeam::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        return $this->teamRepository->findById($value);
    }
}
