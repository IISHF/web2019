<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-22
 * Time: 07:33
 */

namespace App\Application\Event\Command;

/**
 * Interface HasSanctionStatus
 *
 * @package App\Application\Event\Command
 */
interface HasSanctionStatus
{
    /**
     * @return bool
     */
    public function isSanctioned(): bool;

    /**
     * @return string|null
     */
    public function getSanctionNumber(): ?string;

    /**
     * @param string|null $sanctionNumber
     */
    public function setSanctionNumber(?string $sanctionNumber);
}
