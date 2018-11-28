<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 11:51
 */

namespace App\Domain\Model\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 *
 * @package App\Domain\Model\User
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }

    /**
     * @param string $id
     * @param bool   $confirmedOnly
     * @return User|null
     */
    public function findById(string $id, bool $confirmedOnly = false): ?User
    {
        $criteria = ['id' => $id];
        if ($confirmedOnly) {
            $criteria['confirmToken'] = null;
        }
        /** @var User|null $user */
        $user = $this->findOneBy($criteria);
        return $user;
    }

    /**
     * @param string $username
     * @param bool   $confirmedOnly
     * @return User|null
     */
    public function findByUsername(string $username, bool $confirmedOnly = true): ?User
    {
        return $this->findByEmail($username, $confirmedOnly);
    }

    /**
     * @param string $email
     * @param bool   $confirmedOnly
     * @return User|null
     */
    public function findByEmail(string $email, bool $confirmedOnly = true): ?User
    {
        $criteria = ['email' => $email];
        if ($confirmedOnly) {
            $criteria['confirmToken'] = null;
        }
        /** @var User|null $user */
        $user = $this->findOneBy($criteria);
        return $user;
    }

    /**
     * @param string $confirmToken
     * @return User|null
     */
    public function findByConfirmToken(string $confirmToken): ?User
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['confirmToken' => hash('sha256', @hex2bin($confirmToken))]);
        return $user;
    }

    /**
     * @param string $resetPasswordToken
     * @param string $until
     * @return User|null
     */
    public function findByResetPasswordToken(string $resetPasswordToken, string $until = 'now'): ?User
    {
        $expr     = Criteria::expr();
        $criteria = Criteria::create();
        $criteria->where($expr->eq('resetPasswordToken', hash('sha256', @hex2bin($resetPasswordToken))))
                 ->andWhere($expr->gte('resetPasswordUntil', new \DateTimeImmutable($until)))
                 ->andWhere($expr->isNull('confirmToken'));

        $users = $this->matching($criteria);
        if (\count($users) === 1) {
            /** @var User $user */
            $user = $users->first();
            return $user;
        }
        return null;
    }

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User
    {
        $this->_em->persist($user);
        $this->_em->flush();
        return $user;
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
}
