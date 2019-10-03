<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:54
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\IdAware;
use App\Application\NationalGoverningBody\Validator\UniqueNationalGoverningBody;

/**
 * Class CreateNationalGoverningBody
 *
 * @package App\Application\NationalGoverningBody\Command
 *
 * @UniqueNationalGoverningBody()
 */
class CreateNationalGoverningBody implements IdentifiesNationalGoverningBody
{
    use IdAware, NationalGoverningBodyProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }
}
