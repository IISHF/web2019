<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:38
 */

namespace App\Application\Common\Command;

use Ramsey\Uuid\Uuid;

/**
 * Trait UuidAware
 *
 * @package App\Application\Common\Command
 */
trait UuidAware
{
    /**
     * @var string
     */
    private $id;

    /**
     * @return self
     */
    private static function uuid(): string
    {
        return Uuid::uuid4();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
