<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:02
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\Committee\Committee;

/**
 * Class DeleteCommittee
 *
 * @package App\Application\Committee\Command
 */
class DeleteCommittee
{
    use IdAware;

    /**
     * @param Committee $committee
     * @return self
     */
    public static function delete(Committee $committee): self
    {
        return new self($committee->getId());
    }
}
