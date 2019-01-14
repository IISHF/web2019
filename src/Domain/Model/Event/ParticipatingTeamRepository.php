<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 07:47
 */

namespace App\Domain\Model\Event;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
        $team = $this->find($id);
        return $team;
    }

    /**
     * @param Event $event
     * @return iterable|ParticipatingTeam[]
     */
    public function findForEvent(Event $event): iterable
    {
        return $this->createQueryBuilder('t')
                    ->addSelect('c')
                    ->leftJoin('t.contact', 'c')
                    ->where('t.event = :event')
                    ->setParameter('event', $event)
                    ->orderBy('t.name', 'ASC')
                    ->getQuery()
                    ->getResult();
    }


    /**
     * @param ParticipatingTeam $team
     * @return ParticipatingTeam
     */
    public function save(ParticipatingTeam $team): ParticipatingTeam
    {
        $this->_em->persist($team);
        $this->_em->flush();
        return $team;
    }

    /**
     * @param ParticipatingTeam $team
     */
    public function delete(ParticipatingTeam $team): void
    {
        $this->_em->remove($team);
        $this->_em->flush();
    }
}
