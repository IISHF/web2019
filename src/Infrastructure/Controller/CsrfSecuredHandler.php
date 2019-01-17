<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 07:37
 */

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Trait CsrfSecuredHandler
 *
 * @package App\Infrastructure\Controller
 */
trait CsrfSecuredHandler
{
    /**
     * @param object              $command
     * @param string              $token
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     */
    public function handleCsrfCommand(
        object $command,
        string $token,
        Request $request,
        MessageBusInterface $commandBus
    ): void {
        if (!$this->isCsrfTokenValid($token, $request->request->get('_token'))) {
            throw new BadRequestHttpException();
        }
        $commandBus->dispatch($command);
    }

    /**
     * @param string      $id
     * @param string|null $token
     * @return bool
     */
    abstract protected function isCsrfTokenValid(string $id, ?string $token): bool;
}
