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
     * @param string|null $mimeType
     * @param string|null $origin
     * @param int         $page
     * @param int         $limit
     * @return iterable|Pagerfanta|File[]
     */
    public function findPaged(
        ?string $mimeType = null,
        ?string $origin = null,
        int $page = 1,
        int $limit = 30
    ): iterable {
        $queryBuilder = $this->createQueryBuilder('f')
                             ->orderBy('f.createdAt', 'DESC');

        if ($mimeType) {
            $queryBuilder->andWhere('f.mimeType = :mimeType')
                         ->setParameter('mimeType', $mimeType);
        }
        if ($origin) {
            $queryBuilder->andWhere('f.origin = :origin')
                         ->setParameter('origin', $origin);
        }

        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param File $file
     * @param bool $flush
     * @return File
     */
    public function save(File $file, bool $flush): File
    {
        $this->_em->persist($file);
        $this->_em->persist($file->getBinary());
        if ($flush) {
            $this->_em->flush();
        }
        return $file;
    }

    /**
     * @param File $file
     * @param bool $flush
     */
    public function delete(File $file, bool $flush): void
    {
        if ($flush) {
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
        } else {
            $this->_em->remove($file);
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
                         ->executeUpdate('DELETE FROM file_binaries WHERE hash NOT IN (SELECT binary_hash FROM files)');
    }

    /**
     * @param string $hash
     * @return FileBinary|null
     */
    public function findFileBinaryReference(string $hash): ?FileBinary
    {
        $hasHash = $this->_em->getConnection()->fetchColumn(
            'SELECT hash FROM file_binaries WHERE hash = :hash',
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
