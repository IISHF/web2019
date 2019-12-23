<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 07:47
 */

namespace App\Domain\Model\Event\Team;

use App\Domain\Model\Event\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class ParticipatingTeamRepository
 *
 * @package App\Domain\Model\Event
 */
class ParticipatingTeamRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, ParticipatingTeam::class);
    }

    /**
     * @param string $id
     * @return ParticipatingTeam|null
     */
    public function findById(string $id): ?ParticipatingTeam
    {
        /** @var ParticipatingTeam|null $team */
        $team = $this->createQueryBuilderWithAssociations()
                     ->where('t.id = :id')
                     ->setParameter('id', $id)
                     ->getQuery()
                     ->getOneOrNullResult();
        return $team;
    }

    /**
     * @param string $eventId
     * @param string $teamName
     * @return ParticipatingTeam|null
     */
    public function findByName(string $eventId, string $teamName): ?ParticipatingTeam
    {
        /** @var ParticipatingTeam|null $team */
        $team = $this->createQueryBuilderWithAssociations()
                     ->where('t.name = :teamName')
                     ->andWhere('t.event = :eventId')
                     ->setParameter('teamName', $teamName)
                     ->setParameter('eventId', $eventId)
                     ->getQuery()
                     ->getOneOrNullResult();
        return $team;
    }

    /**
     * @param Event $event
     * @return iterable|ParticipatingTeam[]
     */
    public function findForEvent(Event $event): iterable
    {
        return $this->createQueryBuilderWithAssociations()
                    ->where('t.event = :event')
                    ->setParameter('event', $event)
                    ->orderBy('t.name', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryBuilderWithAssociations(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('t')
                    ->addSelect('c', 'e')
                    ->innerJoin('t.event', 'e')
                    ->leftJoin('t.contact', 'c');
    }

    /**
     * @param ParticipatingTeam $team
     * @return ParticipatingTeam
     */
    public function save(ParticipatingTeam $team): ParticipatingTeam
    {
        $this->_em->persist($team);
        $this->_em->persist($team->getContact());
        return $team;
    }

    /**
     * @param ParticipatingTeam $team
     */
    public function delete(ParticipatingTeam $team): void
    {
        $this->_em->remove($team);
        $this->_em->remove($team->getContact());
    }
}
