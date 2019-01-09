<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:54
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\UuidAware;
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
    use UuidAware, NationalGoverningBodyProperties, MutableNationalGoverningBody;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
