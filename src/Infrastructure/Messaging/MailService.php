<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 16:11
 */

namespace App\Infrastructure\Messaging;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\RequestContext;

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
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @var RequestContext
     */
    private $requestContext;

    /**
     * @param array           $defaultSender
     * @param MailerInterface $mailer
     * @param RequestContext  $requestContext
     */
    public function __construct(array $defaultSender, MailerInterface $mailer, RequestContext $requestContext)
    {
        $this->defaultSender  = $defaultSender;
        $this->mailer         = $mailer;
        $this->requestContext = $requestContext;
    }

    /**
     * @param array             $to
     * @param string|null       $subject
     * @param array|null        $from
     * @param string|array|null $template #Template
     * @param array             $parameters
     */
    public function send(
        array $to,
        ?string $subject,
        array $from = null,
        $template = null,
        array $parameters = []
    ): void {
        $message    = $this->createMessage($subject, $template, $parameters);
        $recipients = [];
        foreach ($to as $address => $name) {
            if (is_string($address)) {
                $recipients[] = new Address($address, $name);
            } else {
                $recipients[] = new Address($name);
            }
        }
        $message->to(...$recipients);

        $defaultSender = [];
        foreach ($this->defaultSender as $address => $name) {
            $defaultSender[] = new Address($address, $name);
        }

        $senders = [];
        if ($from !== null) {
            foreach ($from as $address => $name) {
                if (is_string($address)) {
                    $senders[] = new Address($address, $name . ' via iishf.com');
                } else {
                    $senders[] = new Address($name, $name . ' via iishf.com');
                }
            }
        }
        if (empty($senders)) {
            $senders = $defaultSender;
        }
        $message->from(...$senders);

        $sender = reset($defaultSender);
        $message->sender($sender);

        $this->mailer->send($message);
    }

    /**
     * @param string|null       $subject
     * @param string|array|null $template #Template
     * @param array             $parameters
     * @return Email
     */
    private function createMessage(?string $subject, $template = null, array $parameters = []): Email
    {
        if ($template !== null) {
            return $this->addTemplatesToMessage(
                (new TemplatedEmail())->subject($subject),
                $template,
                $parameters
            );
        }
        return (new Email())->subject($subject);
    }

    /**
     * @param TemplatedEmail    $message
     * @param string|array|null $template
     * @param array             $parameters
     * @return TemplatedEmail
     */
    private function addTemplatesToMessage(TemplatedEmail $message, $template, array $parameters = []): TemplatedEmail
    {
        $htmlTemplate = null;
        $textTemplate = null;
        if (is_array($template)) {
            if (count($template) > 1) {
                [$htmlTemplate, $textTemplate] = $template;
            } elseif (count($template) > 0) {
                [$htmlTemplate] = $template;
            }
        } else {
            $htmlTemplate = $template;
        }

        if ($htmlTemplate) {
            $message->htmlTemplate($htmlTemplate);
        }
        if ($textTemplate) {
            $message->textTemplate($textTemplate);
        }
        return $message->context(
            array_merge(
                $parameters,
                [
                    'subject' => $message->getSubject(),
                    'baseUrl' => $this->getBaseUrl(),
                ]
            )
        );
    }

    /**
     * @return string
     */
    private function getBaseUrl(): string
    {
        return $this->requestContext->getScheme() . '://' . $this->requestContext->getHost();
    }
}
