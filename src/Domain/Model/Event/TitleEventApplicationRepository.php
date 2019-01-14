<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-13
 * Time: 12:48
 */

namespace App\Domain\Model\Event;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class TitleEventApplicationRepository
 *
 * @package App\Domain\Model\Event
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
     * @param TitleEvent $titleEvent
     * @return iterable|TitleEventApplication[]
     */
    public function findForEvent(TitleEvent $titleEvent): iterable
    {
        return $this->createQueryBuilder('a')
                    ->where('a.titleEvent = :event')
                    ->setParameter('event', $titleEvent)
                    ->orderBy('a.createdAt', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param TitleEventApplication $application
     * @return TitleEventApplication
     */
    public function save(TitleEventApplication $application): TitleEventApplication
    {
        $this->_em->persist($application);
        $this->_em->flush();
        return $application;
    }

    /**
     * @param TitleEventApplication $application
     */
    public function delete(TitleEventApplication $application): void
    {
        $this->_em->remove($application);
        $this->_em->flush();
    }
}
