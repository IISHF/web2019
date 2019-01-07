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
     * @param string $id
     * @return File|null
     */
    public function findById(string $id): ?File
    {
        return $this->repository->findById($id);
    }

    /**
     * @param File $file
     * @return File
     */
    public function save(File $file): File
    {
        return $this->repository->save($file);
    }

    /**
     * @param File $file
     */
    public function delete(File $file): void
    {
        $this->repository->delete($file);
    }

    /**
     * @param \SplFileInfo $file
     * @param string       $origin
     * @param string|null  $originalName
     * @return File
     */
    public function createFile(\SplFileInfo $file, string $origin, ?string $originalName = null): File
    {
        return $this->createFileWithId(Uuid::uuid4(), $file, $origin, $originalName);
    }

    /**
     * @param string|null  $id
     * @param \SplFileInfo $file
     * @param string|null  $origin
     * @param string|null  $originalName
     * @return File
     */
    public function createFileWithId(
        string $id,
        \SplFileInfo $file,
        string $origin,
        ?string $originalName = null
    ): File {
        $mimeType = self::guessMimeType($file);
        $name     = $id . '.' . self::guessExtension($mimeType);

        return File::create(
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
