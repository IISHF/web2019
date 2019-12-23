<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-03
 * Time: 13:43
 */

namespace App\Infrastructure\Security\MagicLink;

use App\Utils\Text;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
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
     * @param string             $username
     * @param string             $userIp
     * @param string             $userAgent
     * @param DateTimeImmutable $created
     * @return array
     */
    public function createToken(string $username, string $userIp, string $userAgent, DateTimeImmutable $created): array
    {
        $ttl          = $created->modify('+ 15 minutes');
        $token        = random_bytes(32);
        $signatureKey = random_bytes(16);

        $this->db->beginTransaction();
        try {
            $this->db->executeQuery(
                'DELETE FROM login_tokens WHERE ttl < :now OR username = :username',
                [
                    'now'      => new DateTimeImmutable('now'),
                    'username' => $username,
                ],
                [
                    'now' => Type::DATETIME_IMMUTABLE,
                ]
            );

            $this->db->insert(
                'login_tokens',
                [
                    'token'      => hash('sha256', $token),
                    'username'   => $username,
                    'sig_key'    => bin2hex($signatureKey),
                    'user_ip'    => $userIp,
                    'user_agent' => Text::shorten($userAgent, 255),
                    'ttl'        => $ttl,
                    'created'    => $created,
                ],
                [
                    'ttl'     => Type::DATETIME_IMMUTABLE,
                    'created' => Type::DATETIME_IMMUTABLE,
                ]
            );

            $this->db->commit();

            return [bin2hex($token), $signatureKey, $ttl];
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
        $token = hash('sha256', @hex2bin($token));

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
                    'now' => Type::DATETIME_IMMUTABLE,
                ]
            );

            $this->db->commit();

            if (empty($tokenData)) {
                return null;
            }
            [$signatureKey, $ttl] = $tokenData;

            return [@hex2bin($signatureKey), new DateTimeImmutable($ttl)];

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
