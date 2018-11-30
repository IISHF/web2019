<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 15:46
 */

namespace App\Application\User\Command;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class PasswordResetRequestedNotifySubscriber
 *
 * @package App\Application\User\Command
 */
class PasswordResetRequestedNotifySubscriber implements MessageHandlerInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
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
    }
}
