<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 13:43
 */

namespace App\Infrastructure\Security\MagicLink;

use App\Domain\Common\Token;
use App\Utils\Text;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Exception;

/**
 * Class TokenStorage
 *
 * @package App\Infrastructure\Security\MagicLink
 */
class TokenStorage
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param string            $username
     * @param string            $userIp
     * @param string            $userAgent
     * @param DateTimeImmutable $created
     * @return array
     */
    public function createToken(string $username, string $userIp, string $userAgent, DateTimeImmutable $created): array
    {
        $ttl          = $created->modify('+ 15 minutes');
        $token        = Token::random(32, true);
        $signatureKey = Token::random(16, true);

        $this->db->beginTransaction();
        try {
            $this->db->executeQuery(
                'DELETE FROM login_tokens WHERE ttl < :now OR username = :username',
                [
                    'now'      => new DateTimeImmutable('now'),
                    'username' => $username,
                ],
                [
                    'now' => 'datetime_immutable',
                ]
            );

            $this->db->insert(
                'login_tokens',
                [
                    'token'      => Token::hash($token, true),
                    'username'   => $username,
                    'sig_key'    => Token::hex($signatureKey),
                    'user_ip'    => $userIp,
                    'user_agent' => Text::shorten($userAgent, 255),
                    'ttl'        => $ttl,
                    'created'    => $created,
                ],
                [
                    'ttl'     => 'datetime_immutable',
                    'created' => 'datetime_immutable',
                ]
            );

            $this->db->commit();

            return [Token::hex($token), $signatureKey, $ttl];
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * @param string $token
     * @param string $username
     * @return array|null
     */
    public function fetchTokenData(string $token, string $username): ?array
    {
        $token = Token::hash($token);

        $this->db->beginTransaction();
        try {
            $tokenData = $this->db->fetchArray(
                'SELECT sig_key, ttl FROM login_tokens WHERE token = :token AND username = :username',
                [
                    'token'    => $token,
                    'username' => $username,
                ]
            );

            $this->db->executeQuery(
                'DELETE FROM login_tokens WHERE ttl < :now OR token = :token',
                [
                    'now'   => new DateTimeImmutable('now'),
                    'token' => $token,
                ],
                [
                    'now' => 'datetime_immutable',
                ]
            );

            $this->db->commit();

            if (empty($tokenData)) {
                return null;
            }
            [$signatureKey, $ttl] = $tokenData;

            return [Token::binary($signatureKey), new DateTimeImmutable($ttl)];

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
