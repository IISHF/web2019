<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 16:11
 */

namespace App\Infrastructure\Messaging;

use Symfony\Component\Routing\RequestContext;
use Twig\Environment;

/**
 * Class MailService
 *
 * @package App\Infrastructure\Messaging
 */
class MailService
{
    /**
     * @var array
     */
    private $defaultSender;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $templating;

    /**
     * @var RequestContext
     */
    private $requestContext;

    /**
     * @param array          $defaultSender
     * @param \Swift_Mailer  $mailer
     * @param Environment    $templating
     * @param RequestContext $requestContext
     */
    public function __construct(
        array $defaultSender,
        \Swift_Mailer $mailer,
        Environment $templating,
        RequestContext $requestContext
    ) {
        $this->defaultSender  = $defaultSender;
        $this->mailer         = $mailer;
        $this->templating     = $templating;
        $this->requestContext = $requestContext;
    }

    /**
     * @param array             $to
     * @param string|null       $subject
     * @param array|null        $sender
     * @param string|array|null $template #Template
     * @param array             $parameters
     */
    public function send(
        array $to,
        ?string $subject,
        array $sender = null,
        $template = null,
        array $parameters = []
    ): void {
        $message = $this->createMessage($subject, $template, $parameters);
        $message->setTo($to);
        $this->sendMessage($message, $sender);
    }

    /**
     * @param \Swift_Message $message
     * @param array|null     $sender
     */
    public function sendMessage(\Swift_Message $message, array $sender = null): void
    {
        $this->mailer->send($this->prepareMessage($message, $sender));
    }

    /**
     * @param \Swift_Message $message
     * @param array|null     $sender
     * @return \Swift_Message
     */
    private function prepareMessage(\Swift_Message $message, array $sender = null): \Swift_Message
    {
        $mailFrom = $message->getFrom();
        if ($mailFrom) {
            $fromName    = reset($mailFrom);
            $fromAddress = key($mailFrom);
            if ($fromName === null) {
                $fromName = $fromAddress . ' via iishf.com';
            } else {
                $fromName .= ' via iishf.com';
            }
            $message->setFrom([$fromAddress => $fromName]);

            if (!$sender) {
                $sender = $this->defaultSender;
            }
            $message->setSender($sender);
        } else {
            $message->setFrom($this->defaultSender);
        }
        return $message;
    }

    /**
     * @param string|null       $subject
     * @param string|array|null $template #Template
     * @param array             $parameters
     * @return \Swift_Message
     */
    public function createMessage(?string $subject, $template = null, array $parameters = []): \Swift_Message
    {
        $message = new \Swift_Message($subject);
        if ($template !== null) {
            return $this->addTemplatesToMessage(
                $message,
                $template,
                $parameters
            );
        }
        return $message;
    }

    /**
     * @param \Swift_Message    $message
     * @param string|array|null $template
     * @param array             $parameters
     * @return \Swift_Message
     */
    public function addTemplatesToMessage(\Swift_Message $message, $template, array $parameters = []): \Swift_Message
    {
        if (!\is_array($template)) {
            $template = ['text/html' => $template];
        }
        $isFirst = true;
        foreach ($template as $contentType => $name) {
            $body = $this->templating->render(
                $name,
                array_merge(
                    $parameters,
                    [
                        'subject' => $message->getSubject(),
                        'baseUrl' => $this->getBaseUrl(),
                    ]
                )
            );

            if ($isFirst) {
                $message->setBody($body, $contentType);
            } else {
                $message->addPart($body, $contentType);
                $isFirst = false;
            }
        }

        return $message;
    }

    /**
     * @return string
     */
    private function getBaseUrl(): string
    {
        return $this->requestContext->getScheme() . '://' . $this->requestContext->getHost();
    }
}
