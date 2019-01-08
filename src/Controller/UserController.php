<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 10:21
 */

namespace App\Controller;

use App\Application\User\Command\CreateUser;
use App\Application\User\Command\UpdateUser;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\User\Form\CreateUserType;
use App\Infrastructure\User\Form\UpdateUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 *
 * @package App\Controller
 *
 * @Route("/users")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @param Request        $request
     * @param UserRepository $repository
     * @return Response
     */
    public function getList(Request $request, UserRepository $repository): Response
    {
        $page  = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 30);

        return $this->render(
            'user/list.html.twig',
            [
                'users' => $repository->findPaged($page, $limit),
            ]
        );
    }

    /**
     * @Route("/create", methods={"GET", "POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     *
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function create(Request $request, MessageBusInterface $commandBus): Response
    {
        $createUser = CreateUser::create();
        $form       = $this->createForm(CreateUserType::class, $createUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($createUser);
            $this->addFlash(
                'success',
                'The new user has been created.'
            );

            return $this->redirectToRoute('app_user_getlist');
        }

        return $this->render(
            'user/create.html.twig',
            [
                'user' => $createUser,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{user}",
     *     methods={"GET"},
     *     requirements={"user": "%routing.uuid%"}
     * )
     * @ParamConverter(
     *      name="user",
     *      class="App\Domain\Model\User\User",
     *      converter="app.user"
     * )
     *
     * @param User $user
     * @return Response
     */
    public function getDetail(User $user): Response
    {
        return $this->render(
            'user/detail.html.twig',
            [
                'user' => $user,
            ]
        );
    }

    /**
     * @Route(
     *     "/{user}/edit",
     *     methods={"GET", "POST"},
     *     requirements={"user": "%routing.uuid%"}
     * )
     * Security("is_granted('USER_EDIT', user)")
     * @ParamConverter(
     *      name="user",
     *      class="App\Domain\Model\User\User",
     *      converter="app.user"
     * )
     *
     * @param Request             $request
     * @param User                $user
     * @param MessageBusInterface $commandBus
     * @return Response
     */
    public function update(Request $request, User $user, MessageBusInterface $commandBus): Response
    {
        $updateUser = UpdateUser::update($user);
        $form       = $this->createForm(UpdateUserType::class, $updateUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($updateUser);
            $this->addFlash(
                'success',
                'The user has been updated.'
            );

            return $this->redirectToRoute('app_user_getlist');
        }

        return $this->render(
            'user/update.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
