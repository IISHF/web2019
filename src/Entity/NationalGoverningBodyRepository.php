<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-27
 * Time: 17:43
 */

namespace App\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class NationalGoverningBodyRepository
 *
 * @package App\Entity
 */
class NationalGoverningBodyRepository extends EntityRepository
{
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
