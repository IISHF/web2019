<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Application\Common\Command\IdAware;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;

/**
 * Class RemoveNationalGoverningBodyLogo
 *
 * @package App\Application\NationalGoverningBody\Command
 */
class RemoveNationalGoverningBodyLogo
{
    use IdAware;

    /**
     * @param NationalGoverningBody $nationalGoverningBody
     * @return self
     */
    public static function removeFrom(NationalGoverningBody $nationalGoverningBody): self
    {
        return new self($nationalGoverningBody->getId());
    }
}
