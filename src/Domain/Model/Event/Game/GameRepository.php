<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-08
 * Time: 17:58
 */

namespace App\Domain\Model\Event\Game;

use App\Domain\Model\Event\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class GameRepository
 *
 * @package App\Domain\Model\Event\Game
 */
class GameRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Game::class);
    }

    /**
     * @param string $id
     * @return Game|null
     */
    public function findById(string $id): ?Game
    {
        /** @var Game|null $team */
        $team = $this->createQueryBuilderWithAssociations()
                     ->where('g.id = :id')
                     ->setParameter('id', $id)
                     ->getQuery()
                     ->getOneOrNullResult();
        return $team;
    }

    /**
     * @param Event $event
     * @return iterable|Game[]
     */
    public function findForEvent(Event $event): iterable
    {
        return $this->createQueryBuilderWithAssociations()
                    ->where('t.event = :event')
                    ->setParameter('event', $event)
                    ->orderBy('g.gameNumber', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryBuilderWithAssociations(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('g')
                    ->addSelect('e', 'h', 'a')
                    ->innerJoin('g.event', 'e')
                    ->innerJoin('g.homeTeam', 'h')
                    ->innerJoin('g.awayTeam', 'a');
    }

    /**
     * @param Game $team
     * @return Game
     */
    public function save(Game $team): Game
    {
        $this->_em->persist($team);
        return $team;
    }

    /**
     * @param Game $team
     */
    public function delete(Game $team): void
    {
        $this->_em->remove($team);
    }
}
