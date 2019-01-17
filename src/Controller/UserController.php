<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 10:21
 */

namespace App\Controller;

use App\Application\User\Command\CreateUser;
use App\Application\User\Command\DeleteUser;
use App\Application\User\Command\UpdateUser;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Controller\CsrfSecuredHandler;
use App\Infrastructure\Controller\FormHandler;
use App\Infrastructure\Controller\PagingRequest;
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
    use FormHandler, CsrfSecuredHandler;

    /**
     * @Route("", methods={"GET"})
     *
     * @param Request        $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function list(Request $request, UserRepository $userRepository): Response
    {
        $paging = PagingRequest::create($request);
        return $this->render(
            'user/list.html.twig',
            [
                'users' => $userRepository->findPaged($paging->getPage(), $paging->getLimit()),
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

        if ($this->handleForm($createUser, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The new user has been created.');

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render(
            'user/create.html.twig',
            [
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
    public function detail(User $user): Response
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

        if ($this->handleForm($updateUser, $form, $request, $commandBus)) {
            $this->addFlash('success', 'The user has been updated.');

            return $this->redirectToRoute('app_user_list');
        }

        return $this->render(
            'user/update.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(
     *     "/{user}/delete",
     *     methods={"POST", "DELETE"},
     *     requirements={"user": "%routing.uuid%"}
     * )
     * Security("is_granted('USER_DELETE', user)")
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
    public function delete(Request $request, User $user, MessageBusInterface $commandBus): Response
    {
        $deleteUser = DeleteUser::delete($user);

        $this->handleCsrfCommand($deleteUser, 'user_delete_' . $user->getId(), $request, $commandBus);

        $this->addFlash('success', 'The user has been deleted.');

        return $this->redirectToRoute('app_user_list');
    }
}
