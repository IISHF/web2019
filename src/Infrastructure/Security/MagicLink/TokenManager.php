<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 13:47
 */

namespace App\Infrastructure\Security\MagicLink;

use App\Domain\Model\User\UserRepository;
use App\Infrastructure\Messaging\MailService;
use App\Infrastructure\Security\MagicLink\Exception\TokenExpiredException;
use App\Infrastructure\Security\MagicLink\Exception\TokenNotFoundException;
use App\Infrastructure\Security\MagicLink\Exception\TokenTamperedException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class TokenManager
 *
 * @package App\Infrastructure\Security\MagicLink
 */
class TokenManager
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var MailService
     */
    private $mailService;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param TokenStorage          $tokenStorage
     * @param UserRepository        $userRepository
     * @param MailService           $mailService
     * @param UrlGeneratorInterface $urlGenerator
     * @param LoggerInterface       $logger
     */
    public function __construct(
        TokenStorage $tokenStorage,
        UserRepository $userRepository,
        MailService $mailService,
        UrlGeneratorInterface $urlGenerator,
        LoggerInterface $logger
    ) {
        $this->tokenStorage   = $tokenStorage;
        $this->userRepository = $userRepository;
        $this->mailService    = $mailService;
        $this->urlGenerator   = $urlGenerator;
        $this->logger         = $logger;
    }

    /**
     * @param string                  $username
     * @param string                  $userIp
     * @param string                  $userAgent
     * @param string|null             $redirectTo
     * @param \DateTimeImmutable|null $created
     * @return array
     */
    public function createToken(
        string $username,
        string $userIp,
        string $userAgent,
        ?string $redirectTo,
        \DateTimeImmutable $created
    ): array {
        try {
            $user = $this->userRepository->findByEmail($username);
            if (!$user) {
                $ex = new UsernameNotFoundException(sprintf('Username "%s" is not allowed.', $username));
                $ex->setUsername($username);
                throw $ex;
            }

            /** @var string $token */
            /** @var string $signatureKey */
            /** @var \DateTimeImmutable $ttl */
            [$token, $signatureKey, $ttl] = $this->tokenStorage->createToken($username, $userIp, $userAgent, $created);

            $urlParams = $this->signParams(
                [
                    'email'       => $username,
                    'token'       => $token,
                    'redirect_to' => $redirectTo,
                ],
                $signatureKey
            );

            $url = $this->urlGenerator->generate('login_magic_link', $urlParams, UrlGeneratorInterface::ABSOLUTE_URL);

            $this->logger->info(
                'Created magic link token {token} (valid until {ttl}) for user {username} from {ip} using {userAgent}',
                [
                    'token'     => $token,
                    'ttl'       => $ttl->format(\DateTimeImmutable::W3C),
                    'username'  => $username,
                    'ip'        => $userIp,
                    'userAgent' => $userAgent,
                    'url'       => $url,
                ]
            );

            $this->mailService->send(
                [
                    $user->getEmail() => $user->getName(),
                ],
                'Your login to iishf.com',
                null,
                'email/login_magic.html.twig',
                [
                    'user'      => $user,
                    'created'   => $created,
                    'ttl'       => $ttl,
                    'userIp'    => $userIp,
                    'userAgent' => $userAgent,
                    'url'       => $url,
                ]
            );

            return [
                'url' => $url,
                'ttl' => $ttl,
            ];

        } catch (\Exception $e) {
            $this->logger->error(
                'Creating a magic link token for user {username} from {ip} using {userAgent} failed: {message}',
                [
                    'username'  => $username,
                    'ip'        => $userIp,
                    'userAgent' => $userAgent,
                    'message'   => $e->getMessage(),
                    'exception' => $e,
                ]
            );
            throw $e;
        }
    }

    /**
     * @param string      $token
     * @param string      $username
     * @param string      $signature
     * @param string|null $redirectTo
     * @throws AuthenticationException
     */
    public function assertTokenValid(
        string $token,
        string $username,
        string $signature,
        ?string $redirectTo = null
    ): void {
        if (empty($username)) {
            $this->logger->error(
                'Validating a magic link token {token} failed: token tampered, username not specified',
                [
                    'token' => $token,
                ]
            );
            throw new TokenTamperedException('Invalid username.');
        }
        if (strlen($token) !== 64 || !preg_match('/^[0-9a-f]{64}$/', $token)) {
            $this->logger->error(
                'Validating a magic link token {token} for user {username} failed: token tampered, token invalid',
                [
                    'token'    => $token,
                    'username' => $username,
                ]
            );
            throw new TokenTamperedException('Invalid token.');
        }

        if (strlen($signature) !== 64 || !preg_match('/^[0-9a-f]{64}$/', $signature)) {
            $this->logger->error(
                'Validating a magic link token {token} for user {username} failed: token tampered, signature {signature} invalid',
                [
                    'token'     => $token,
                    'username'  => $username,
                    'signature' => $signature,
                ]
            );
            throw new TokenTamperedException('Invalid signature.');
        }

        $tokenData = $this->tokenStorage->fetchTokenData($token, $username);

        if ($tokenData === null) {
            $this->logger->notice(
                'Validating a magic link token {token} for user {username} failed: token not found',
                [
                    'token'    => $token,
                    'username' => $username,
                ]
            );
            throw new TokenNotFoundException();
        }

        /** @var string $signatureKey */
        /** @var \DateTimeImmutable $ttl */
        [$signatureKey, $ttl] = $tokenData;

        $signatureCheck = $this->createParamSignature(
            [
                'email'       => $username,
                'token'       => $token,
                'redirect_to' => $redirectTo,
            ],
            $signatureKey
        );

        if (!hash_equals($signatureCheck, $signature)) {
            $this->logger->error(
                'Validating a magic link token {token} for user {username} failed: token tampered, signature check failed with signature {signature}',
                [
                    'token'       => $token,
                    'username'    => $username,
                    'signature'   => $signature,
                    'requiredSig' => $signatureCheck,
                ]
            );
            throw new TokenTamperedException('The parameters did not pass the signature check.');
        }

        if ($ttl < new \DateTimeImmutable('now')) {
            $this->logger->notice(
                'Validating a magic link token {token} for user {username} failed: token expired on {ttl}',
                [
                    'token'    => $token,
                    'username' => $username,
                    'ttl'      => $ttl->format(\DateTimeImmutable::W3C),
                ]
            );
            throw new TokenExpiredException($ttl);
        }
    }

    /**
     * @param array  $params
     * @param string $signatureKey
     * @return string
     */
    private function createParamSignature(array $params, string $signatureKey): string
    {
        ksort($params, SORT_STRING);

        return hash_hmac('sha256', http_build_query($params), $signatureKey);
    }

    /**
     * @param array  $params
     * @param string $signatureKey
     * @return array
     */
    private function signParams(array $params, string $signatureKey): array
    {
        $params['sig'] = $this->createParamSignature($params, $signatureKey);
        return $params;
    }
}
