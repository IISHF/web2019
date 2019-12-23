<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 09.01.17
 * Time: 08:25
 */

namespace App\Infrastructure\Security\MagicLink\Exception;

use DateTimeImmutable;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class TokenExpiredException
 *
 * @package App\Infrastructure\Security\MagicLink\Exception
 */
class TokenExpiredException extends AuthenticationException
{
    /**
     * @var DateTimeImmutable
     */
    private $ttl;

    /**
     * @param DateTimeImmutable $ttl
     * @param string             $message
     * @param int                $code
     * @param Exception|null    $previous
     */
    public function __construct(
        DateTimeImmutable $ttl,
        string $message = 'The token has expired.',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->ttl = $ttl;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getTtl(): DateTimeImmutable
    {
        return $this->ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'The magic link has expired on {{ ttl }}.';
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageData(): array
    {
        return [
            '{{ ttl }}'     => $this->ttl->format('d.m.y H:i'),
            '{{ ttl_iso }}' => $this->ttl->format('Y-m-d H:i'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize(
            [
                $this->ttl,
                parent::serialize(),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str): void
    {
        [$this->ttl, $parentData] = unserialize($str, [TokenInterface::class]);

        parent::unserialize($parentData);
    }
}
