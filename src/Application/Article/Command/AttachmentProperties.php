<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 14:12
 */

namespace App\Application\Article\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait AttachmentProperties
 *
 * @package App\Application\Article\Command
 *
 * @property \SplFileInfo $file
 */
trait AttachmentProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $articleId;

    /**
     * @return string
     */
    public function getArticleId(): string
    {
        return $this->articleId;
    }

    /**
     * @return \SplFileInfo
     */
    public function getFile(): \SplFileInfo
    {
        return $this->file;
    }

    /**
     * @param \SplFileInfo $file
     * @return $this
     */
    public function setFile(\SplFileInfo $file): self
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @param string $id
     * @param string $designator
     * @return string
     */
    private static function createReference(string $id, string $designator): string
    {
        return $id . '@' . $designator;
    }
}
