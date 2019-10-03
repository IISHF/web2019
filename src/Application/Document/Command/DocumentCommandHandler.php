<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:49
 */

namespace App\Application\Document\Command;

use App\Application\Common\Command\CommandHandler;
use App\Application\File\FileFactory;
use App\Domain\Common\Urlizer;
use App\Domain\Model\Document\Document;
use App\Domain\Model\Document\DocumentRepository;
use App\Domain\Model\Document\DocumentVersion;
use App\Domain\Model\File\File;

/**
 * Class DocumentCommandHandler
 *
 * @package App\Application\Document\Command
 */
abstract class DocumentCommandHandler implements CommandHandler
{
    /**
     * @var DocumentRepository
     */
    protected $documentRepository;

    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * @param DocumentRepository $documentRepository
     * @param FileFactory        $fileFactory
     */
    public function __construct(DocumentRepository $documentRepository, FileFactory $fileFactory)
    {
        $this->documentRepository = $documentRepository;
        $this->fileFactory        = $fileFactory;
    }

    /**
     * @param string $id
     * @return Document
     */
    protected function getDocument(string $id): Document
    {
        $document = $this->documentRepository->findById($id);
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
        $version = $this->documentRepository->findVersionById($id);
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
                return ($tryDocument = $this->documentRepository->findBySlug($slug)) !== null
                    && $tryDocument->getId() !== $id;
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
                return ($tryVersion = $this->documentRepository->findVersionBySlug($documentSlug, $slug)) !== null
                    && $tryVersion->getId() !== $id;
            }
        );
    }

    /**
     * @param \SplFileInfo $file
     * @param string       $originalName
     * @return File
     */
    protected function createFile(\SplFileInfo $file, string $originalName): File
    {
        return $this->fileFactory->createFile($file, DocumentVersion::FILE_ORIGIN, $originalName);
    }
}
