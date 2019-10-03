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
 * Trait IdAware
 *
 * @package App\Application\Common\Command
 */
trait IdAware
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
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(?string $id = null): self
    {
        $this->id = $id ?: self::createUuid();
        return $this;
    }
}
