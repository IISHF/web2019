<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 14:12
 */

namespace App\Application\Article\Command;

use SplFileInfo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait AttachmentProperties
 *
 * @package App\Application\Article\Command
 *
 * @property SplFileInfo $file
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
     * @return SplFileInfo
     */
    public function getFile(): SplFileInfo
    {
        return $this->file;
    }
}
