<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 15:36
 */

namespace App\Domain\Model\File;

use App\Domain\Model\Common\CreateTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class FileBinary
 *
 * @package App\Domain\Model\File
 *
 * @ORM\Entity()
 * @ORM\Table(name="file_binaries")
 */
class FileBinary
{
    use CreateTracking;

    /**
     * @ORM\Column(name="hash", type="string", length=40, options={"fixed": true})
     * @ORM\Id
     *
     * @var string
     */
    private $hash;

    /**
     * @ORM\Column(name="data", type="blob", length=4294967295)
     *
     * @var string|resource
     */
    private $data;

    /**
     * @param string $hash
     * @param string $data
     * @return FileBinary
     */
    public static function fromString(string $hash, string $data): self
    {
        return new self($hash, $data);
    }

    /**
     * @param string $hash
     * @param string $data
     */
    private function __construct(string $hash, $data)
    {
        $this->data = $data;
        $this->setHash($hash)
             ->setData($data)
             ->initCreateTracking();
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     * @return $this
     */
    private function setHash(string $hash): self
    {
        Assert::length($hash, 40);
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return resource|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param resource|string $data
     * @return $this
     */
    private function setData($data): self
    {
        Assert::lengthBetween($data, 1, 4294967295);
        $this->data = $data;
        return $this;
    }
}
