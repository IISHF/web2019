<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 12:55
 */

namespace App\Domain\Model\Event;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class EventRepository
 *
 * @package App\Domain\Model\Event
 */
class EventRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Event::class);
    }

    /**
     * @param string      $id
     * @param string|null $type #Entity
     * @return Event|null
     */
    public function findById(string $id, ?string $type = null): ?Event
    {
        $queryBuilder = $this->createQueryBuilderWithAssociations()
                             ->where('e.id = :id')
                             ->setParameter('id', $id);
        if ($type !== null) {
            $queryBuilder->andWhere('e INSTANCE OF :type')
                         ->setParameter('type', $this->_em->getClassMetadata($type));
        }

        /** @var Event|null $event */
        $event = $queryBuilder->getQuery()
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
     * @param Event $event
     * @return Event
     */
    public function save(Event $event): Event
    {
        $this->_em->persist($event);
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
}
