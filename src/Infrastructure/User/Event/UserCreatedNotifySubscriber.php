<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 15:46
 */

namespace App\Infrastructure\User\Event;

use App\Application\User\Command\UserCreated;
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
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
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
    }
}
