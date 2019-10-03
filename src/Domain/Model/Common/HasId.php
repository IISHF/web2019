<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 09:13
 */

namespace App\Domain\Model\Common;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Trait HasId
 *
 * @package App\Domain\Model\Common
 */
trait HasId
{
    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

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
    private function setId(string $id): self
    {
        Assert::uuid($id);
        $this->id = $id;
        return $this;
    }
}
