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
