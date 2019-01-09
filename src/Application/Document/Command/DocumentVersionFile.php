<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 07:37
 */

namespace App\Application\Document\Command;

/**
 * Trait DocumentVersionFile
 *
 * @package App\Application\Document\Command
 */
trait DocumentVersionFile
{
    /**
     * @Assert\File(
     *      maxSize="16M",
     *      mimeTypes={
     *          "application/pdf",
     *          "application/x-pdf",
     *          "application/zip",
     *          "application/vnd.ms-excel",
     *          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
     *          "application/msword",
     *          "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
     *          "application/vnd.ms-powerpoint",
     *          "application/vnd.openxmlformats-officedocument.presentationml.presentation"
     *      }
     * )
     * @Assert\Type("SplFileInfo")
     * @Assert\NotNull()
     *
     * @var \SplFileInfo
     */
    private $file;

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

}
