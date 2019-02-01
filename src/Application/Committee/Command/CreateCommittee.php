<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:01
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateCommittee
 *
 * @package App\Application\Committee\Command
 */
class CreateCommittee
{
    use UuidAware, CommitteeProperties;

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
