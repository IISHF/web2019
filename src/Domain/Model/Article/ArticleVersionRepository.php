<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 10:29
 */

namespace App\Domain\Model\Article;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;

/**
 * Class ArticleVersionRepository
 *
 * @package App\Domain\Model\Article
 */
class ArticleVersionRepository extends LogEntryRepository implements ServiceEntityRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $entityClass = ArticleVersion::class;
        /** @var EntityManagerInterface $manager */
        $manager = $registry->getManagerForClass($entityClass);
        parent::__construct($manager, $manager->getClassMetadata($entityClass));
    }
}
