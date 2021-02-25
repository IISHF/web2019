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
use Collator;
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
     * @return StaffMember[]
     */
    public function findAll(): array
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
     * @return StaffMember[]
     */
    public function findPresidium(): array
    {
        $presidium = array_filter(
            $this->findAll(),
            static fn(StaffMember $m): bool => $m->hasRole(StaffMember::ROLE_PRESIDIUM)
        );
        $collator  = Collator::create('en-US');
        usort(
            $presidium,
            static function (StaffMember $a, StaffMember $b) use ($collator): int {
                $aIndex = $a->getSortOrder();
                $bIndex = $b->getSortOrder();
                if ($aIndex === $bIndex) {
                    return $collator->compare($a->getTitle(), $b->getTitle());
                }
                return $aIndex - $bIndex;
            }
        );
        return $presidium;
    }

    /**
     * @return StaffMember[]
     */
    public function findOfficers(): array
    {
        return array_filter(
            $this->findAll(),
            static fn(StaffMember $m): bool => !$m->hasRole(StaffMember::ROLE_PRESIDIUM)
        );
    }

    /**
     * @return string[]
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
