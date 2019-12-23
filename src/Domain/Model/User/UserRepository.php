<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 11:51
 */

namespace App\Domain\Model\User;

use App\Domain\Common\Repository\DoctrinePaging;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;
use function count;

/**
 * Class UserRepository
 *
 * @package App\Domain\Model\User
 */
class UserRepository extends ServiceEntityRepository
{
    use DoctrinePaging;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, User::class);
    }

    /**
     * @param string $id
     * @return User|null
     */
    public function findById(string $id): ?User
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['id' => $id]);
        return $user;
    }

    /**
     * @param string $username
     * @return User|null
     */
    public function findByEmail(string $username): ?User
    {
        /** @var User|null $user */
        $user = $this->findOneBy(['email' => $username]);
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
                 ->andWhere($expr->gte('resetPasswordUntil', new DateTimeImmutable($until)))
                 ->andWhere($expr->isNull('confirmToken'));

        $users = $this->matching($criteria);
        if (count($users) === 1) {
            /** @var User $user */
            $user = $users->first();
            return $user;
        }
        return null;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return iterable|Pagerfanta|User[]
     */
    public function findPaged(int $page = 1, int $limit = 30): iterable
    {
        $queryBuilder = $this->createQueryBuilder('u')
                             ->orderBy('u.lastName', 'ASC')
                             ->addOrderBy('u.firstName', 'ASC');
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User
    {
        $this->_em->persist($user);
        return $user;
    }

    /**
     * @param User $user
     */
    public function delete(User $user): void
    {
        $this->_em->remove($user);
    }
}
