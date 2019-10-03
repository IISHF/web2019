<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:01
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\IdAware;

/**
 * Class CreateCommittee
 *
 * @package App\Application\Committee\Command
 */
class CreateCommittee
{
    use IdAware, CommitteeProperties;

    /**
     * @return self
     */
    public static function create(): self
    {
        return new self(self::createUuid());
    }
}
