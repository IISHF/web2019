<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-27
 * Time: 17:43
 */

namespace App\Domain\Model\NationalGoverningBody;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class NationalGoverningBodyRepository
 *
 * @package App\Domain\Model\NationalGoverningBody
 */
class NationalGoverningBodyRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, NationalGoverningBody::class);
    }

    /**
     * @param string $id
     * @return NationalGoverningBody|null
     */
    public function findById(string $id): ?NationalGoverningBody
    {
        /** @var NationalGoverningBody|null $ngb */
        $ngb = $this->find($id);
        return $ngb;
    }

    /**
     * @param string $acronym
     * @return NationalGoverningBody|null
     */
    public function findByAcronym(string $acronym): ?NationalGoverningBody
    {
        /** @var NationalGoverningBody|null $ngb */
        $ngb = $this->findOneBy(['acronym' => $acronym]);
        return $ngb;
    }

    /**
     * @param string $name
     * @return NationalGoverningBody|null
     */
    public function findByName(string $name): ?NationalGoverningBody
    {
        /** @var NationalGoverningBody|null $ngb */
        $ngb = $this->findOneBy(['name' => $name]);
        return $ngb;
    }

    /**
     * @param string $slug
     * @return NationalGoverningBody|null
     */
    public function findBySlug(string $slug): ?NationalGoverningBody
    {
        /** @var NationalGoverningBody|null $ngb */
        $ngb = $this->findOneBy(['slug' => $slug]);
        return $ngb;
    }

    /**
     * @param string $iocCode
     * @return NationalGoverningBody|null
     */
    public function findByIocCode(string $iocCode): ?NationalGoverningBody
    {
        /** @var NationalGoverningBody|null $ngb */
        $ngb = $this->findOneBy(['iocCode' => $iocCode]);
        return $ngb;
    }

    /**
     * @param string $email
     * @return NationalGoverningBody|null
     */
    public function findByEmail(string $email): ?NationalGoverningBody
    {
        /** @var NationalGoverningBody|null $ngb */
        $ngb = $this->findOneBy(['email' => $email]);
        return $ngb;
    }

    /**
     * @return iterable|NationalGoverningBody[]
     */
    public function findAll(): iterable
    {
        return $this->createQueryBuilder('n')
                    ->orderBy('n.country', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @param NationalGoverningBody $ngb
     * @return NationalGoverningBody
     */
    public function save(NationalGoverningBody $ngb): NationalGoverningBody
    {
        $this->_em->persist($ngb);
        $this->_em->flush();
        return $ngb;
    }

    /**
     * @param NationalGoverningBody $ngb
     */
    public function delete(NationalGoverningBody $ngb): void
    {
        $this->_em->remove($ngb);
        $this->_em->flush();
    }
}
