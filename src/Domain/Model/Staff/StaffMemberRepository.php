<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 07:36
 */

namespace App\Domain\Model\Staff;

use App\Domain\Model\Common\TagProvider;
use App\Utils\Tags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class StaffMemberRepository
 *
 * @package App\Domain\Model\Staff
 */
class StaffMemberRepository extends ServiceEntityRepository implements TagProvider
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, StaffMember::class);
    }

    /**
     * @param string $id
     * @return StaffMember|null
     */
    public function findById(string $id): ?StaffMember
    {
        /** @var StaffMember|null $member */
        $member = $this->find($id);
        return $member;
    }

    /**
     * @return iterable|StaffMember[]
     */
    public function findAll(): iterable
    {
        return $this->createQueryBuilder('m')
                    ->addSelect('mi')
                    ->leftJoin('m.image', 'mi')
                    ->orderBy('m.lastName', 'ASC')
                    ->addOrderBy('m.firstName', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return string[]|
     */
    public function findAvailableRoles(): array
    {
        return Tags::createTagList(
            $this->createQueryBuilder('m')
                 ->select('m.roles')
                 ->getQuery()
                 ->getArrayResult()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function findAvailableTags(): array
    {
        return $this->findAvailableRoles();
    }

    /**
     * @param StaffMember $member
     * @return StaffMember
     */
    public function save(StaffMember $member): StaffMember
    {
        $this->_em->persist($member);
        return $member;
    }

    /**
     * @param StaffMember $member
     */
    public function delete(StaffMember $member): void
    {
        $this->_em->remove($member);
    }
}
