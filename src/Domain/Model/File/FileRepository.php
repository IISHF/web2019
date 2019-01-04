<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 17:08
 */

namespace App\Domain\Model\File;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * Class FileRepository
 *
 * @package App\Domain\Model\File
 */
class FileRepository extends ServiceEntityRepository
{
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

            $this->_em->getConnection()
                      ->createQueryBuilder()
                      ->delete('file_binary')
                      ->where('hash NOT IN (SELECT binary_hash FROM files)')
                      ->execute();

            $this->_em->commit();
        } catch (\Exception $e) {
            $this->_em->rollback();
            throw $e;
        }
    }

    /**
     * @param string $hash
     * @return FileBinary|null
     */
    public function findFileBinaryReference(string $hash): ?FileBinary
    {
        $hasHash = $this->_em->createQueryBuilder()
                             ->select('fb.hash')
                             ->from(FileBinary::class, 'fb')
                             ->where('fb.hash = :hash')
                             ->setParameter('hash', $hash)
                             ->getQuery()
                             ->getOneOrNullResult(Query::HYDRATE_SCALAR);

        if ($hasHash !== null && $hash === $hasHash['hash']) {
            return $this->_em->getReference(FileBinary::class, $hash);
        }
        return null;
    }
}
