<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 15:46
 */

namespace App\Infrastructure\User\Event;

use App\Application\User\Command\PasswordResetRequested;
use App\Infrastructure\Messaging\MailService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PasswordResetRequestedNotifySubscriber
 *
 * @package App\Infrastructure\User\Event
 */
class PasswordResetRequestedNotifySubscriber implements MessageHandlerInterface
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
     * @param PasswordResetRequested $event
     */
    public function __invoke(PasswordResetRequested $event): void
    {
        $resetUrl = $this->urlGenerator->generate(
            'app_account_resetpassword',
            ['token' => $event->getResetPasswordToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $this->mailService->send(
            [
                $event->getUser()->getEmail() => $event->getUser()->getName(),
            ],
            'Password Reset Requested',
            null,
            'email/password_reset_requested.html.twig',
            [
                'url' => $resetUrl,
            ]
        );
    }
}
