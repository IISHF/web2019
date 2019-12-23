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
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
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
     * @return QueryBuilder
     */
    private function createQueryBuilderWithBinary(): QueryBuilder
    {
        return $this->createQueryBuilder('f')
                    ->addSelect('fb')
                    ->innerJoin('f.binary', 'fb');
    }

    /**
     * @param string|null $mimeType
     * @param string|null $origin
     * @return QueryBuilder
     */
    private function createQueryBuilderWithFilter(
        ?string $mimeType = null,
        ?string $origin = null
    ): QueryBuilder {
        $queryBuilder = $this->createQueryBuilder('f');
        if ($mimeType) {
            $queryBuilder->andWhere('f.mimeType = :mimeType')
                         ->setParameter('mimeType', $mimeType);
        }
        if ($origin) {
            $queryBuilder->andWhere('f.origin = :origin')
                         ->setParameter('origin', $origin);
        }
        return $queryBuilder;
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
        $queryBuilder = $this->createQueryBuilderWithFilter($mimeType, $origin)
                             ->orderBy('f.createdAt', 'DESC');

        return $this->createPager($queryBuilder, $page, $limit);
    }

    /**
     * @param string|null $mimeType
     * @param string|null $origin
     * @return iterable|File[]
     */
    public function findAll(?string $mimeType = null, ?string $origin = null): iterable
    {
        $queryBuilder = $this->createQueryBuilderWithFilter($mimeType, $origin)
                             ->orderBy('f.createdAt', 'ASC');

        foreach ($queryBuilder->getQuery()->iterate() as $row) {
            yield $row[0];
        }
    }

    /**
     * @param File $file
     * @return File
     */
    public function save(File $file): File
    {
        $this->_em->persist($file);
        $this->_em->persist($file->getBinary());
        return $file;
    }

    /**
     * @param File $file
     */
    public function delete(File $file): void
    {
        $binary     = $file->getBinary();
        $usageCount = $this->_em->getConnection()
                                ->fetchColumn(
                                    'SELECT COUNT(f.id) AS c FROM files f WHERE f.binary_hash = :hash',
                                    [
                                        'hash' => $binary->getHash(),
                                    ],
                                    0,
                                    [
                                        'hash' => 'string',
                                    ]
                                );
        if ($usageCount < 2) {
            $this->_em->remove($binary);
        }
        $this->_em->remove($file);
    }

    /**
     * @param string[] $ids
     */
    public function deleteById(string ...$ids): void
    {
        $db = $this->_em->getConnection();
        $db->beginTransaction();
        try {
            $fileHashes = array_column(
                $db->fetchAll(
                    <<<'SQL'
SELECT fb.hash, COUNT(f.id) AS c
FROM file_binaries fb
LEFT JOIN files f ON fb.hash = f.binary_hash
WHERE fb.hash IN (SELECT binary_hash FROM files WHERE id IN (:ids))
GROUP BY fb.hash
HAVING c < 2
SQL
                    ,
                    [
                        'ids' => $ids,
                    ],
                    [
                        'ids' => Connection::PARAM_STR_ARRAY,
                    ]
                ),
                'hash'
            );
            $db->executeUpdate(
                'DELETE FROM files WHERE id IN (:ids)',
                [
                    'ids' => $ids,
                ],
                [
                    'ids' => Connection::PARAM_STR_ARRAY,
                ]
            );
            $this->deleteBinariesByHash(...$fileHashes);
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * @param string[] $hashes
     * @return int
     */
    public function deleteBinariesByHash(string ...$hashes): int
    {
        return $this->_em->getConnection()
                         ->executeUpdate(
                             'DELETE FROM file_binaries WHERE hash IN (:hashes)',
                             [
                                 'hashes' => $hashes,
                             ],
                             [
                                 'hashes' => Connection::PARAM_STR_ARRAY,
                             ]
                         );
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
                'hash' => 'string',
            ]
        );

        if ($hash === $hasHash) {
            /** @var FileBinary $reference */
            $reference = $this->_em->getReference(FileBinary::class, $hash);
            return $reference;
        }
        return null;
    }

    /**
     * @param bool $onlyUnused
     * @return iterable
     */
    public function findAllBinaries(bool $onlyUnused = false): iterable
    {
        $queryBuilder = $this->_em->createQueryBuilder()
                                  ->select('fb', 'COUNT(f) AS count')
                                  ->from(FileBinary::class, 'fb')
                                  ->leftJoin(File::class, 'f', 'WITH', 'f.binary = fb.hash')
                                  ->groupBy('fb');
        if ($onlyUnused) {
            $queryBuilder->having('COUNT(f) < 1');
        }

        foreach ($queryBuilder->getQuery()->iterate() as $i => $row) {
            yield $i => [$row[0][0], (int)$row[$i]['count']];
        }
    }
}
