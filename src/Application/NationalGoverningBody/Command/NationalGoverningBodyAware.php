<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:12
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;

/**
 * Trait NationalGoverningBodyAware
 *
 * @package App\Application\NationalGoverningBody\Command
 */
trait NationalGoverningBodyAware
{
    /**
     * @var NationalGoverningBody
     */
    private $nationalGoverningBody;

    /**
     * @return NationalGoverningBody
     */
    public function getNationalGoverningBody(): NationalGoverningBody
    {
        return $this->nationalGoverningBody;
    }
}
