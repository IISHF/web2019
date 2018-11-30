<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 15:46
 */

namespace App\Infrastructure\User\Event;

use App\Application\User\Command\UserCreated;
use App\Infrastructure\Messaging\MailService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserCreatedNotifySubscriber
 *
 * @package App\Infrastructure\User\Event
 */
class UserCreatedNotifySubscriber implements MessageHandlerInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param MailService           $mailService
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, MailService $mailService)
    {
        $this->urlGenerator = $urlGenerator;
        $this->mailService  = $mailService;
    }

    /**
     * @param UserCreated $event
     */
    public function __invoke(UserCreated $event): void
    {
        $confirmUrl = $this->urlGenerator->generate(
            'app_account_confirmuser',
            ['token' => $event->getConfirmToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this->mailService->send(
            [
                $event->getUser()->getEmail() => $event->getUser()->getName(),
            ],
            'User Created',
            null,
            'email/user_created.html.twig',
            [
                'url' => $confirmUrl,
            ]
        );
    }
}
