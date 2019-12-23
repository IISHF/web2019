<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 17:18
 */

namespace App\Application\File;

use App\Domain\Model\File\File;
use App\Domain\Model\File\FileBinary;
use App\Domain\Model\File\FileRepository;
use Ramsey\Uuid\Uuid;
use SplFileInfo;
use Symfony\Component\Mime\MimeTypes;

/**
 * Class FileFactory
 *
 * @package App\Application\File
 */
class FileFactory
{
    /**
     * @var FileRepository
     */
    private $fileRepository;

    /**
     * @param FileRepository $fileRepository
     */
    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param SplFileInfo $file
     * @param string      $origin
     * @param string|null $originalName
     * @return File
     */
    public function createFile(SplFileInfo $file, string $origin, ?string $originalName = null): File
    {
        return $this->createFileWithId(Uuid::uuid4(), $file, $origin, $originalName);
    }

    /**
     * @param string|null $id
     * @param SplFileInfo $file
     * @param string|null $origin
     * @param string|null $originalName
     * @return File
     */
    public function createFileWithId(
        string $id,
        SplFileInfo $file,
        string $origin,
        ?string $originalName = null
    ): File {
        $mimeTypes  = MimeTypes::getDefault();
        $mimeType   = $mimeTypes->guessMimeType($file->getPathname()) ?? 'application/octet-stream';
        $extensions = $mimeTypes->getExtensions($mimeType);
        if (empty($extensions)) {
            $extension = 'bin';
        } else {
            $extension = reset($extensions);
        }
        $name = $id . '.' . $extension;

        if ($originalName !== null && !pathinfo($originalName, PATHINFO_EXTENSION)) {
            $originalName .= '.' . $extension;
        }

        return new File(
            $id,
            $name,
            $originalName,
            $file->getSize(),
            $mimeType,
            $origin,
            $this->ensureBinary($file)
        );
    }

    /**
     * @param SplFileInfo $file
     * @return FileBinary
     */
    private function ensureBinary(SplFileInfo $file): FileBinary
    {
        $hash   = sha1_file($file->getPathname());
        $binary = $this->fileRepository->findFileBinaryReference($hash);
        if (!$binary) {
            $binary = FileBinary::fromString($hash, file_get_contents($file->getPathname()));
        }
        return $binary;
    }
}
