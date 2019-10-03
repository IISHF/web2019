<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:14
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;

/**
 * Class DeleteNationalGoverningBody
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class DeleteNationalGoverningBody
{
    use IdAware;

    /**
     * @param NationalGoverningBody $nationalGoverningBody
     * @return self
     */
    public static function delete(NationalGoverningBody $nationalGoverningBody): self
    {
        return new self($nationalGoverningBody->getId());
    }
}
