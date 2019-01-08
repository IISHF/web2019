<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 17:08
 */

namespace App\Domain\Model\File;

use App\Domain\Common\Repository\DoctrinePaging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Pagerfanta\Pagerfanta;

/**
 * Class FileRepository
 *
 * @package App\Domain\Model\File
 */
class FileRepository extends ServiceEntityRepository
{
    use DoctrinePaging;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, File::class);
    }

    /**
     * @param string $id
     * @return File|null
     */
    public function findById(string $id): ?File
    {
        /** @var File|null $file */
        $file = $this->find($id);
        return $file;
    }

    /**
     * @param string $name
     * @return File|null
     */
    public function findByName(string $name): ?File
    {
        /** @var File|null $file */
        $file = $this->findOneBy(['name' => $name]);
        return $file;
    }

    /**
     * @param string $id
     * @return File|null
     */
    public function findByIdWithBinary(string $id): ?File
    {
        /** @var File|null $file */
        $file = $this->createQueryBuilderWithBinary()
                     ->where('f.id = :id')
                     ->setParameter('id', $id)
                     ->getQuery()
                     ->getOneOrNullResult();
        return $file;
    }

    /**
     * @param string $name
     * @return File|null
     */
    public function findByNameWithBinary(string $name): ?File
    {
        /** @var File|null $file */
        $file = $this->createQueryBuilderWithBinary()
                     ->where('f.name = :name')
                     ->setParameter('name', $name)
                     ->getQuery()
                     ->getOneOrNullResult();
        return $file;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    private function createQueryBuilderWithBinary(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('f')
                    ->addSelect('fb')
                    ->innerJoin('f.binary', 'fb');
    }

    /**
     * @param int $page
     * @param int $limit
     * @return iterable|Pagerfanta|File[]
     */
    public function findPaged(int $page = 1, int $limit = 30): iterable
    {
        $queryBuilder = $this->createQueryBuilder('f')
                             ->orderBy('f.createdAt', 'DESC');
        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param File $file
     * @return File
     */
    public function save(File $file): File
    {
        $this->_em->persist($file);
        $this->_em->persist($file->getBinary());
        $this->_em->flush();
        return $file;
    }

    /**
     * @param File $file
     */
    public function delete(File $file): void
    {
        $this->_em->beginTransaction();
        try {
            $this->_em->remove($file);
            $this->_em->flush();
            $this->cleanupFileBinaries();
            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
        }
    }

    /**
     * @param string[] $ids
     */
    public function deleteById(string ...$ids): void
    {
        $db = $this->_em->getConnection();
        $db->beginTransaction();
        try {
            $db->executeUpdate(
                'DELETE FROM files WHERE id IN (:ids)',
                [
                    'ids' => $ids,
                ],
                [
                    'ids' => Connection::PARAM_STR_ARRAY,
                ]
            );
            $this->cleanupFileBinaries();
            $db->commit();
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     */
    public function cleanupFileBinaries(): int
    {
        return $this->_em->getConnection()
                         ->executeUpdate('DELETE FROM file_binary WHERE hash NOT IN (SELECT binary_hash FROM files)');
    }

    /**
     * @param string $hash
     * @return FileBinary|null
     */
    public function findFileBinaryReference(string $hash): ?FileBinary
    {
        $hasHash = $this->_em->getConnection()->fetchColumn(
            'SELECT hash FROM file_binary WHERE hash = :hash',
            [
                'hash' => $hash,
            ],
            0,
            [
                'hash' => Type::STRING,
            ]
        );

        if ($hash === $hasHash) {
            return $this->_em->getReference(FileBinary::class, $hash);
        }
        return null;
    }
}
