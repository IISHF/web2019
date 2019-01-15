<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 12:55
 */

namespace App\Domain\Model\Event;

use App\Domain\Model\Common\TagProvider;
use App\Utils\Tags;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class EventRepository
 *
 * @package App\Domain\Model\Event
 */
class EventRepository extends ServiceEntityRepository implements TagProvider
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Event::class);
    }

    /**
     * @param string $id
     * @return Event|null
     */
    public function findById(string $id): ?Event
    {
        /** @var Event|null $event */
        $event = $this->createQueryBuilderWithAssociations()
                      ->where('e.id = :id')
                      ->setParameter('id', $id)
                      ->getQuery()
                      ->getOneOrNullResult();
        return $event;
    }

    /**
     * @param int    $season
     * @param string $slug
     * @return Event|null
     */
    public function findBySlug(int $season, string $slug): ?Event
    {
        /** @var Event|null $event */
        $event = $this->createQueryBuilderWithAssociations()
                      ->where('e.slug = :slug')
                      ->andWhere('e.season = :season')
                      ->setParameter('slug', $slug)
                      ->setParameter('season', $season)
                      ->getQuery()
                      ->getOneOrNullResult();
        return $event;
    }

    /**
     * @param int    $season
     * @param string $name
     * @return Event|null
     */
    public function findByName(int $season, string $name): ?Event
    {
        /** @var Event|null $event */
        $event = $this->createQueryBuilderWithAssociations()
                      ->where('e.name = :name')
                      ->andWhere('e.season = :season')
                      ->setParameter('name', $name)
                      ->setParameter('season', $season)
                      ->getQuery()
                      ->getOneOrNullResult();
        return $event;
    }

    /**
     * @param int $season
     * @return iterable|EuropeanChampionship[]
     */
    public function findEuropeanChampionShipsForSeason(int $season): iterable
    {
        return $this->createQueryBuilderWithEventType(EuropeanChampionship::class)
                    ->andWhere('e.season = :season')
                    ->setParameter('season', $season)
                    ->orderBy('e.name', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param int $season
     * @return iterable|EuropeanChampionship[]
     */
    public function findEuropeanCupsForSeason(int $season): iterable
    {
        return $this->createQueryBuilderWithEventType(EuropeanCup::class)
                    ->andWhere('e.season = :season')
                    ->setParameter('season', $season)
                    ->orderBy('e.name', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param int $season
     * @return iterable|EuropeanChampionship[]
     */
    public function findTournamentsForSeason(int $season): iterable
    {
        return $this->createQueryBuilderWithEventType(Tournament::class)
                    ->andWhere('e.season = :season')
                    ->setParameter('season', $season)
                    ->orderBy('e.name', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryBuilderWithAssociations(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('e')
                    ->addSelect('h', 'v')
                    ->leftJoin('e.host', 'h')
                    ->leftJoin('e.venue', 'v');
    }

    /**
     * @param string $class
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryBuilderWithEventType(string $class): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilderWithAssociations()
                    ->where('e INSTANCE OF :type')
                    ->setParameter('type', $this->_em->getClassMetadata($class));
    }

    /**
     * @param Event $event
     * @return Event
     */
    public function save(Event $event): Event
    {
        $this->_em->persist($event);
        if ($host = $event->getHost()) {
            $this->_em->persist($host);
        }
        $this->_em->flush();
        return $event;
    }

    /**
     * @param Event $event
     */
    public function delete(Event $event): void
    {
        $this->_em->remove($event);
        $this->_em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function findAvailableTags(): array
    {
        return Tags::createTagList(
            $this->createQueryBuilder('e')
                 ->select('e.tags')
                 ->getQuery()
                 ->getArrayResult()
        );
    }
}
