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
use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/**
 * Class FileManager
 *
 * @package App\Application\File
 */
class FileManager
{
    /**
     * @var FileRepository
     */
    private $repository;

    /**
     * FileManager constructor.
     *
     * @param FileRepository $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param File $file
     * @return File
     */
    public function save(File $file): File
    {
        return $this->repository->save($file, true);
    }

    /**
     * @param File $file
     */
    public function delete(File $file): void
    {
        $this->repository->delete($file);
    }

    /**
     * @param string|null  $id
     * @param string|null  $name
     * @param \SplFileInfo $file
     * @return File
     */
    public function createFile(?string $id, ?string $name, \SplFileInfo $file): File
    {
        $id       = $id ?? (string)Uuid::uuid4();
        $mimeType = self::guessMimeType($file);
        if ($name === null) {
            $name = $id . '.' . self::guessExtension($mimeType);
        }

        return File::create(
            $id,
            $name,
            $file->getSize(),
            $mimeType,
            $this->ensureBinary($file)
        );
    }

    /**
     * @param \SplFileInfo $file
     * @return FileBinary
     */
    private function ensureBinary(\SplFileInfo $file): FileBinary
    {
        $hash   = sha1_file($file->getPathname());
        $binary = $this->repository->findFileBinaryReference($hash);
        if (!$binary) {
            $binary = FileBinary::fromString($hash, file_get_contents($file->getPathname()));
        }
        return $binary;
    }

    /**
     * @param \SplFileInfo $file
     * @param string       $default
     * @return string
     */
    private static function guessMimeType(\SplFileInfo $file, string $default = 'application/octet-stream'): string
    {
        return MimeTypeGuesser::getInstance()->guess($file->getPathname()) ?? $default;
    }

    /**
     * @param string $mimeType
     * @param string $default
     * @return string
     */
    private static function guessExtension(string $mimeType, string $default = 'bin'): string
    {
        return ExtensionGuesser::getInstance()->guess($mimeType) ?? $default;
    }
}
