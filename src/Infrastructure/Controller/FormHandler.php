<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 07:24
 */

namespace App\Infrastructure\Controller;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Trait FormHandler
 *
 * @package App\Infrastructure\Controller
 */
trait FormHandler
{
    /**
     * @param object              $command
     * @param FormInterface       $form
     * @param Request             $request
     * @param MessageBusInterface $commandBus
     * @return bool
     */
    private function handleForm(
        object $command,
        FormInterface $form,
        Request $request,
        MessageBusInterface $commandBus
    ): bool {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commandBus->dispatch($command);
            return true;
        }
        return false;
    }
}
