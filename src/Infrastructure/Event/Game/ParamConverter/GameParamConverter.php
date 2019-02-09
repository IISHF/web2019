<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:54
 */

namespace App\Infrastructure\Event\Game\ParamConverter;

use App\Domain\Model\Event\Game\Game;
use App\Domain\Model\Event\Game\GameRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GameParamConverter
 *
 * @package App\Infrastructure\Event\Game\ParamConverter
 */
class GameParamConverter extends LoaderParamConverter
{
    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @param GameRepository $gameRepository
     */
    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === Game::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        return $this->gameRepository->findById($value);
    }
}
