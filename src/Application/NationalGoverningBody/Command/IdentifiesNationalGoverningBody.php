<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:47
 */

namespace App\Application\NationalGoverningBody\Command;

/**
 * Interface IdentifiesNationalGoverningBody
 *
 * @package App\Application\NationalGoverningBody\Command
 */
interface IdentifiesNationalGoverningBody
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getAcronym(): string;

    /**
     * @return string
     */
    public function getSlug(): string;

    /**
     * @return string
     */
    public function getIocCode(): string;

    /**
     * @return string
     */
    public function getEmail(): string;
}
