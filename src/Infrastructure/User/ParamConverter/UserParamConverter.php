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
    private $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
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
    protected function loadObject($value, ParamConverter $configuration): ?object
    {
        return $this->repository->findById($value);
    }
}
