<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:38
 */

namespace App\Application\Common\Command;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait UuidAware
 *
 * @package App\Application\Common\Command
 */
trait UuidAware
{
    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $id;

    /**
     * @return string
     */
    private static function createUuid(): string
    {
        return (string)Uuid::uuid4();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
