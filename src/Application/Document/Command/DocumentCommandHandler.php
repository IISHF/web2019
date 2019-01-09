<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:49
 */

namespace App\Application\Document\Command;

use App\Application\File\FileManager;
use App\Domain\Common\Urlizer;
use App\Domain\Model\Document\Document;
use App\Domain\Model\Document\DocumentRepository;
use App\Domain\Model\Document\DocumentVersion;
use App\Domain\Model\File\File;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class DocumentCommandHandler
 *
 * @package App\Application\Document\Command
 */
abstract class DocumentCommandHandler implements MessageHandlerInterface
{
    /**
     * @var DocumentRepository
     */
    protected $repository;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @param DocumentRepository $repository
     * @param FileManager        $fileManager
     */
    public function __construct(DocumentRepository $repository, FileManager $fileManager)
    {
        $this->repository  = $repository;
        $this->fileManager = $fileManager;
    }

    /**
     * @param string $id
     * @return Document
     */
    protected function getDocument(string $id): Document
    {
        $document = $this->repository->findById($id);
        if (!$document) {
            throw new \OutOfBoundsException('No document found for id ' . $id);
        }
        return $document;
    }

    /**
     * @param string $id
     * @return DocumentVersion
     */
    protected function getDocumentVersion(string $id): DocumentVersion
    {
        $version = $this->repository->findVersionById($id);
        if (!$version) {
            throw new \OutOfBoundsException('No document version found for id ' . $id);
        }
        return $version;
    }

    /**
     * @param string      $title
     * @param string|null $id
     * @return string
     */
    protected function findSuitableDocumentSlug(string $title, ?string $id): string
    {
        return Urlizer::urlizeUnique(
            $title,
            function (string $slug) use ($id) {
                return ($tryDocument = $this->repository->findBySlug($slug)) !== null && $tryDocument->getId() !== $id;
            }
        );
    }

    /**
     * @param string      $documentSlug
     * @param string      $version
     * @param string|null $id
     * @return string
     */
    protected function findSuitableDocumentVersionSlug(string $documentSlug, string $version, ?string $id): string
    {
        return Urlizer::urlizeUnique(
            $version,
            function (string $slug) use ($id, $documentSlug) {
                return ($tryVersion = $this->repository->findVersionBySlug($documentSlug, $slug)) !== null
                    && $tryVersion->getId() !== $id;
            }
        );
    }

    /**
     * @param \SplFileInfo $file
     * @return File
     */
    protected function createFile(\SplFileInfo $file): File
    {
        return $this->fileManager->createFile($file, DocumentVersion::FILE_ORIGIN);
    }
}
