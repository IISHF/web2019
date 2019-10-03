<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:10
 */

namespace App\Application\Event\Command\EuropeanCup;

use App\Application\Common\Command\IdAware;
use App\Application\Event\Command\EventProperties;
use App\Application\Event\Command\TitleEventProperties;

/**
 * Class CreateEuropeanCup
 *
 * @package App\Application\Event\Command\EuropeanCup
 */
class CreateEuropeanCup
{
    use IdAware, EventProperties, TitleEventProperties, EuropeanCupProperties;

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
