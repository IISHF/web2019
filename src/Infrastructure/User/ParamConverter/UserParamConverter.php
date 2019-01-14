<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 12:33
 */

namespace App\Infrastructure\User\ParamConverter;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserParamConverter
 *
 * @package App\Infrastructure\User\ParamConverter
 */
class UserParamConverter extends LoaderParamConverter
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === User::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        return $this->userRepository->findById($value);
    }
}
