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
     * @param string $slug
     * @return Event|null
     */
    public function findBySlug(string $slug): ?Event
    {
        /** @var Event|null $event */
        $event = $this->createQueryBuilderWithAssociations()
                      ->where('e.slug = :slug')
                      ->setParameter('slug', $slug)
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
                    ->addSelect('o', 'v')
                    ->leftJoin('e.organizer', 'o')
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
