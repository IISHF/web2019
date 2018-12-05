<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 16:39
 */

namespace App\Controller\Admin;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class UserController
 *
 * @package App\Controller\Admin
 *
 * @Route("/admin/users")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", methods={"GET"})
     *
     * @param UserRepository      $userRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function list(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        return JsonResponse::fromJsonString(
            $serializer->serialize(
                array_map(
                    function (User $user) {
                        return [
                            'id'         => $user->getId(),
                            'first_name' => $user->getFirstName(),
                            'last_name'  => $user->getLastName(),
                            'name'       => $user->getName(),
                            'email'      => $user->getEmail(),
                        ];
                    },
                    $userRepository->findAll()
                ),
                'json'
            )
        );
    }
}
