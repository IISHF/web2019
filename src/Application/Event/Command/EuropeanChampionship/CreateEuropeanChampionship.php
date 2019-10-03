<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:10
 */

namespace App\Application\Event\Command\EuropeanChampionship;

use App\Application\Common\Command\IdAware;
use App\Application\Event\Command\EventProperties;
use App\Application\Event\Command\TitleEventProperties;

/**
 * Class CreateEuropeanChampionship
 *
 * @package App\Application\Event\Command\EuropeanChampionship
 */
class CreateEuropeanChampionship
{
    use IdAware, EventProperties, TitleEventProperties;

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
