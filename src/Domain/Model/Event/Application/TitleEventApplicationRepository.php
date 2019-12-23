<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-13
 * Time: 12:48
 */

namespace App\Domain\Model\Event\Application;

use App\Domain\Model\Event\TitleEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TitleEventApplicationRepository
 *
 * @package App\Domain\Model\Event\Application
 */
class TitleEventApplicationRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, TitleEventApplication::class);
    }

    /**
     * @param string $id
     * @return TitleEventApplication|null
     */
    public function findById(string $id): ?TitleEventApplication
    {
        /** @var TitleEventApplication|null $application */
        $application = $this->createQueryBuilderWithAssociations()
                            ->where('a.id = :id')
                            ->setParameter('id', $id)
                            ->getQuery()
                            ->getOneOrNullResult();
        return $application;
    }

    /**
     * @param TitleEvent $titleEvent
     * @return iterable|TitleEventApplication[]
     */
    public function findForEvent(TitleEvent $titleEvent): iterable
    {
        return $this->findForEventId($titleEvent->getId());
    }

    /**
     * @param string $titleEventId
     * @return iterable|TitleEventApplication[]
     */
    public function findForEventId(string $titleEventId): iterable
    {
        return $this->createQueryBuilderWithAssociations()
                    ->where('a.titleEvent = :event')
                    ->setParameter('event', $titleEventId)
                    ->orderBy('a.createdAt', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param string $eventId
     * @param string $clubName
     * @return TitleEventApplication|null
     */
    public function findByClub(string $eventId, string $clubName): ?TitleEventApplication
    {
        /** @var TitleEventApplication|null $application */
        $application = $this->createQueryBuilderWithAssociations()
                            ->where('a.applicantClub= :clubName')
                            ->andWhere('a.titleEvent = :eventId')
                            ->setParameter('clubName', $clubName)
                            ->setParameter('eventId', $eventId)
                            ->getQuery()
                            ->getOneOrNullResult();
        return $application;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryBuilderWithAssociations(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('a')
                    ->addSelect('e', 'v')
                    ->innerJoin('a.titleEvent', 'e')
                    ->innerJoin('a.venue', 'v');
    }

    /**
     * @param TitleEventApplication $application
     * @return TitleEventApplication
     */
    public function save(TitleEventApplication $application): TitleEventApplication
    {
        $this->_em->persist($application);
        return $application;
    }

    /**
     * @param TitleEventApplication $application
     */
    public function delete(TitleEventApplication $application): void
    {
        $this->_em->remove($application);
    }
}
