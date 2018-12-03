<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 16:45
 */

namespace App\Infrastructure\Security;

use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ReCaptchaClient
 *
 * @package App\Infrastructure\Security
 */
class ReCaptchaClient
{
    public const  GOOGLE_CLIENT_URL = 'https://www.google.com/recaptcha/api.js';
    private const GOOGLE_API_URL    = 'https://www.google.com/recaptcha/api/siteverify';
    private const RESPONSE_FIELD    = 'g-recaptcha-response';

    /**
     * @var string
     */
    private $reCaptchaSecret;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param string          $reCaptchaSecret
     * @param bool            $debug
     * @param LoggerInterface $logger
     */
    public function __construct($reCaptchaSecret, bool $debug, LoggerInterface $logger)
    {
        $this->reCaptchaSecret = $reCaptchaSecret;
        $this->debug           = $debug;
        $this->logger          = $logger;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function validateRequest(Request $request): bool
    {
        return $this->validate(
            $request->request->get(self::RESPONSE_FIELD),
            $request->getClientIp()
        );
    }

    /**
     * @param string      $response
     * @param string|null $clientIp
     * @return bool
     */
    public function validate(string $response, ?string $clientIp = null): bool
    {
        $this->logger->debug(sprintf('Google reCAPTCHA response: %s', $response));

        $postData = http_build_query(
            [
                'secret'   => $this->reCaptchaSecret,
                'response' => $response,
                'remoteip' => $clientIp,
            ]
        );

        $this->logger->debug(sprintf('Google reCAPTCHA POST data: %s', $postData));

        try {
            $client         = new Client();
            $googleResponse = $client->request(
                'POST',
                self::GOOGLE_API_URL,
                [
                    'body'    => $postData,
                    'headers' => [
                        'Content-type' => 'application/x-www-form-urlencoded',
                    ],
                ]
            );
            $body           = $googleResponse->getBody()
                                             ->getContents();

            $this->logger->debug(sprintf('Google reCAPTCHA response received data: %s', $body));

            if (($result = json_decode($body, true)) && isset($result['success']) && $result['success']) {
                $this->logger->info('Google reCAPTCHA passed');

                return true;
            }

            $this->logger->notice('Google reCAPTCHA failed');

            return false;

        } catch (\Exception $e) {
            if ($this->debug) {
                $this->logger->notice(
                    sprintf(
                        'Google reCAPTCHA bypassed in debug mode - error [%s]: %s',
                        get_class($e),
                        $e->getMessage()
                    )
                );

                return true;
            }
            throw $e;
        }
    }
}
