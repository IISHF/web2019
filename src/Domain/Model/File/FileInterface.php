<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-07
 * Time: 14:51
 */

namespace App\Domain\Model\File;


use DateTimeImmutable;

/**
 * Class File
 *
 * @package App\Domain\Model\File
 *
 * @ORM\Entity(repositoryClass="FileRepository")
 * @ORM\Table(
 *      name="files",
 *      indexes={
 *          @ORM\Index(name="idx_file_origin", columns={"origin"})
 *      }
 * )
 */
interface FileInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string|null
     */
    public function getOriginalName(): ?string;

    /**
     * @return int
     */
    public function getSize(): int;

    /**
     * @return string
     */
    public function getMimeType(): string;

    /**
     * @return string
     */
    public function getOrigin(): string;

    /**
     * @return FileBinary
     */
    public function getBinary(): FileBinary;

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable;
}
