<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:22
 */

namespace App\Domain\Model\Committee;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class CommitteeRepository
 *
 * @package App\Domain\Model\Committee
 */
class CommitteeRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Committee::class);
    }

    /**
     * @param string $id
     * @return Committee|null
     */
    public function findById(string $id): ?Committee
    {
        /** @var Committee|null $committee */
        $committee = $this->find($id);
        return $committee;
    }

    /**
     * @param string $slug
     * @return Committee|null
     */
    public function findBySlug(string $slug): ?Committee
    {
        /** @var Committee|null $committee */
        $committee = $this->findOneBy(['slug' => $slug]);
        return $committee;
    }

    /**
     * @param string $title
     * @return Committee|null
     */
    public function findByTitle(string $title): ?Committee
    {
        /** @var Committee|null $committee */
        $committee = $this->findOneBy(['title' => $title]);
        return $committee;
    }

    /**
     * @param string $id
     * @return Committee|null
     */
    public function findByIdWithMembers(string $id): ?Committee
    {
        /** @var Committee|null $committee */
        $committee = $this->createQueryBuilderWithMembers()
                          ->where('c.id = :id')
                          ->setParameter('id', $id)
                          ->getQuery()
                          ->getOneOrNullResult();
        return $committee;
    }

    /**
     * @param string $slug
     * @return Committee|null
     */
    public function findBySlugWithMembers(string $slug): ?Committee
    {
        /** @var Committee|null $committee */
        $committee = $this->createQueryBuilderWithMembers()
                          ->where('c.slug = :slug')
                          ->setParameter('slug', $slug)
                          ->getQuery()
                          ->getOneOrNullResult();
        return $committee;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryBuilderWithMembers(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('c')
                    ->addSelect('cm')
                    ->leftJoin('c.members', 'cm')
                    ->orderBy('cm.memberType', 'ASC')
                    ->addOrderBy('cm.lastName', 'ASC')
                    ->addOrderBy('cm.firstName', 'ASC');
    }

    /**
     * @return iterable|Committee[]
     */
    public function findAll(): iterable
    {
        return $this->createQueryBuilderWithMembers()
                    ->orderBy('c.title', 'ASC')
                    ->addOrderBy('cm.memberType', 'ASC')
                    ->addOrderBy('cm.lastName', 'ASC')
                    ->addOrderBy('cm.firstName', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param Committee $committee
     * @return Committee
     */
    public function save(Committee $committee): Committee
    {
        $this->_em->persist($committee);
        foreach ($committee->getMembers() as $member) {
            $this->_em->persist($member);
        }
        return $committee;
    }

    /**
     * @param Committee $committee
     */
    public function delete(Committee $committee): void
    {
        $this->_em->remove($committee);
        foreach ($committee->getMembers() as $member) {
            $this->_em->remove($member);
        }
    }

    /**
     * @param string $id
     * @return CommitteeMember|null
     */
    public function findMemberById(string $id): ?CommitteeMember
    {
        /** @var CommitteeMember|null $member */
        $member = $this->createMemberQueryBuilder()
                       ->where('cm.id = :id')
                       ->setParameter('id', $id)
                       ->getQuery()
                       ->getOneOrNullResult();
        return $member;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createMemberQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        return $this->_em->createQueryBuilder()
                         ->select('cm', 'c')
                         ->from(CommitteeMember::class, 'cm')
                         ->join('cm.committee', 'c');
    }

    /**
     * @param CommitteeMember $member
     * @return CommitteeMember
     */
    public function saveMember(CommitteeMember $member): CommitteeMember
    {
        $this->_em->persist($member);
        return $member;
    }

    /**
     * @param CommitteeMember $member
     */
    public function deleteMember(CommitteeMember $member): void
    {
        $member->removeFromCommittee();
        $this->_em->remove($member);
    }
}
