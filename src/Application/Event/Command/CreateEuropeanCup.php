<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:10
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateEuropeanCup
 *
 * @package App\Application\Event\Command
 */
class CreateEuropeanCup
{
    use UuidAware, EventProperties, TitleEventProperties, EuropeanCupProperties;

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
        $this->id     = $id;
        $this->season = idate('Y') + 1;
    }
}
