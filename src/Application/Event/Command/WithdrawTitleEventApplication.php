<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:29
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\Event\TitleEventApplication;

/**
 * Class WithdrawTitleEventApplication
 *
 * @package App\Application\Event\Command
 */
class WithdrawTitleEventApplication
{
    use UuidAware;

    /**
     * @param TitleEventApplication $application
     * @return self
     */
    public static function withdraw(TitleEventApplication $application): self
    {
        return new self($application->getId());
    }

    /**
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }
}
