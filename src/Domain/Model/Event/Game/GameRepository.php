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
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

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
        /** @var Game|null $game */
        $game = $this->createQueryBuilderWithAssociations()
                     ->where('g.id = :id')
                     ->setParameter('id', $id)
                     ->getQuery()
                     ->getOneOrNullResult();
        return $game;
    }

    /**
     * @param Event $event
     * @return iterable|Game[]
     */
    public function findForEvent(Event $event): iterable
    {
        return $this->createQueryBuilderWithAssociations()
                    ->where('g.event = :event')
                    ->setParameter('event', $event)
                    ->orderBy('g.gameNumber', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return QueryBuilder
     */
    private function createQueryBuilderWithAssociations(): QueryBuilder
    {
        return $this->createQueryBuilder('g')
                    ->addSelect('e', 'h', 'a')
                    ->innerJoin('g.event', 'e')
                    ->leftJoin('g.homeTeam', 'h')
                    ->leftJoin('g.awayTeam', 'a');
    }

    /**
     * @param Game[] $games
     * @return Game[]
     */
    public function save(Game ...$games): array
    {
        if (empty($games)) {
            return [];
        }
        $currentGames = $this->findBy(['event' => $games[0]->getEvent()], ['dateTimeUtc' => 'ASC']);
        foreach ($games as $game) {
            $newGame = $game;
            foreach ($currentGames as $currentGame) {
                /** @var Game $currentGame */
                if ($currentGame->getId() === $game->getId()) {
                    $newGame = null;
                    break;
                }
            }
            if ($newGame) {
                $currentGames[] = $newGame;
            }
        }
        usort(
            $currentGames,
            static function (Game $a, Game $b) {
                return $a->getDateTimeUtc() <=> $b->getDateTimeUtc();
            }
        );
        $gameNumber = 1;
        foreach ($currentGames as $game) {
            /** @var Game $game */
            $game->setGameNumber($gameNumber++);
            $this->_em->persist($game);
        }
        return $games;
    }

    /**
     * @param Game $game
     */
    public function delete(Game $game): void
    {
        $games      = $this->findBy(['event' => $game->getEvent()], ['dateTimeUtc' => 'ASC']);
        $gameNumber = 1;
        foreach ($games as $g) {
            /** @var Game $g */
            if ($g->getId() !== $game->getId()) {
                $g->setGameNumber($gameNumber++);
            }
        }
        $this->_em->remove($game);
    }
}
