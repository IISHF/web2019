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
 * Class UpdateCommittee
 *
 * @package App\Application\Committee\Command
 */
class UpdateCommittee
{
    use IdAware, CommitteeProperties;

    /**
     * @param Committee $committee
     * @return self
     */
    public static function update(Committee $committee): self
    {
        return new self(
            $committee->getId(),
            $committee->getTitle(),
            $committee->getDescription()
        );
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $description
     */
    private function __construct(
        string $id,
        string $title,
        string $description
    ) {
        $this->id          = $id;
        $this->title       = $title;
        $this->description = $description;
    }
}
