<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 15:46
 */

namespace App\Infrastructure\User\Event;

use App\Application\User\Command\UserUnconfirmed;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UserUnconfirmedNotifySubscriber
 *
 * @package App\Infrastructure\User\Event
 */
class UserUnconfirmedNotifySubscriber implements MessageHandlerInterface
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
     * @param UserUnconfirmed $event
     */
    public function __invoke(UserUnconfirmed $event): void
    {
        $confirmUrl = $this->urlGenerator->generate(
            'app_account_confirmuser',
            ['token' => $event->getConfirmToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
