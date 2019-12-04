<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 17:28
 */

namespace App\Command\Migrate;

use App\Application\Document\Command\CreateDocument;
use App\Application\Document\Command\CreateDocumentVersion;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DocumentCommand
 *
 * @package App\Command\Migrate
 */
class DocumentCommand extends CommandWithFilesystem
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('app:migrate:document')
            ->setDescription('Migrates documents from legacy database.')
            ->setHelp('This command allows you to migrate documents from a IISHF legacy database.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Migrate documents rom legacy database');


        $documentsPath = $this->legacyPath . '/www/wwwroot/regulations';
        if (!self::isDirectoryReadable($documentsPath)) {
            $this->io->warning('Legacy document path ' . $documentsPath . ' does not exist or is not readable.');
            return 1;
        }
        $this->io->comment('Full legacy document path ' . $documentsPath);

        $metaDataMap = [
            'rulesofthegame'    => [
                'title' => 'Official Rule Book',
                'tags'  => ['Rules of the Game', 'Official Rulebook', 'Rules'],
            ],
            'regulations'       => ['title' => 'Regulations', 'tags' => ['Regulation']],
            'constitution'      => ['title' => 'Constitution', 'tags' => ['Constitution']],
            'itc'               => ['title' => 'International Team Certification', 'tags' => ['ITC']],
            'sanctioning'       => [
                'title' => 'Sanctioning Workflow for Non Title Events',
                'tags'  => ['Sanctioning', 'Non-Title Event'],
            ],
            'application'       => [
                'title' => 'Candidature Form for IISHF Non Title Event (B-Event)',
                'tags'  => ['Application', 'Non-Title Event'],
            ],
            'hosting-te'        => [
                'title' => 'Guideline for hosting of Title Events',
                'tags'  => ['Guideline', 'Hosting', 'Title Event'],
            ],
            'sanctioning-te'    => [
                'title' => 'Sanctioning Workflow for Title Events',
                'tags'  => ['Sanctioning', 'Title Event'],
            ],
            'application-te'    => [
                'title' => 'Candidature Form for IISHF Title Event (A-Event)',
                'tags'  => ['Application', 'Title Event'],
            ],
            'checklist-host-te' => [
                'title' => 'Title Event Checklist for the Host',
                'tags'  => ['Checklist', 'Host', 'Hosting', 'Title Event'],
            ],
            'participating-te'  => [
                'title' => 'Guidelines for teams participating at Title Events',
                'tags'  => ['Participation', 'Title Event'],
            ],
            'deadlines-iishf'   => ['title' => 'IISHF Deadlines', 'tags' => ['Deadlines', 'IISHF']],
            'disciplinary'      => ['title' => 'Disciplinary Regulations', 'tags' => ['Disciplinary', 'Regulation']],
            'financial'         => ['title' => 'Financial Regulations', 'tags' => ['Financial', 'Regulation']],
            'tournaments'       => ['title' => 'Tournament Regulations', 'tags' => ['Tournament', 'Regulation']],
        ];

        $it            = new \GlobIterator(
            $documentsPath . '/*.*',
            \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS
        );
        $documents     = [];
        $documentCount = 0;
        $this->io->section('Looking for files in ' . $documentsPath . '...');
        foreach ($it as $file) {
            /** @var \SplFileInfo $file */
            $matches = [];
            if (preg_match('/^([\w-]+)\.(\d{4})\.\w+$/', $file->getFilename(), $matches)
                && isset($metaDataMap[$matches[1]])
            ) {
                $documents[$matches[1]][$matches[2]] = $file;
                $documentCount++;
                $this->io->text('[FOUND]   ' . $file->getFilename());
            } else {
                $this->io->text('[SKIPPED] ' . $file->getFilename());
            }
        }
        $this->io->success('Found ' . $documentCount . ' files.');

        array_walk(
            $documents,
            function (array &$d) {
                ksort($d, SORT_NUMERIC);
            }
        );
        ksort($documents, SORT_STRING);

        $results = [];
        $this->io->section('Importing documents...');
        $this->io->progressStart($documentCount);
        $this->beginTransaction();
        try {
            $i = 1;
            foreach ($documents as $type => $typeDocuments) {
                /** @var bool|string $createDoc */
                $createDoc         = true;
                $typeDocumentIndex = count($typeDocuments);
                foreach ($typeDocuments as $year => $typeDocument) {
                    /** @var \SplFileInfo $typeDocument */
                    $typeDocumentIndex--;

                    $version   = 'Season ' . $year;
                    $validFrom = new \DateTimeImmutable($year . '-01-01');
                    if ($typeDocumentIndex === 0) {
                        $validUntil = null;
                    } else {
                        $validUntil = new \DateTimeImmutable($year . '-12-31');
                    }
                    $validityStr = self::formatValidity($validFrom, $validUntil);

                    if ($createDoc === true) {
                        $createDocument = CreateDocument::create();
                        $createDocument->setTitle($metaDataMap[$type]['title'])
                                       ->setTags($metaDataMap[$type]['tags'])
                                       ->setVersion($version)
                                       ->setValidFrom($validFrom)
                                       ->setValidUntil($validUntil)
                                       ->setFile($typeDocument);

                        $this->dispatchCommand($createDocument);

                        $createDoc = $createDocument->getId();
                        $results[] = [
                            $i,
                            $typeDocument->getFilename(),
                            $createDocument->getTitle(),
                            $version,
                            $validityStr,
                        ];
                    } else {
                        $createVersion = CreateDocumentVersion::create($createDoc);
                        $createVersion->setVersion($version)
                                      ->setValidFrom($validFrom)
                                      ->setValidUntil($validUntil)
                                      ->setFile($typeDocument);

                        $this->dispatchCommand($createVersion);

                        $results[] = [
                            $i,
                            $typeDocument->getFilename(),
                            '',
                            $version,
                            $validityStr,
                        ];
                    }

                    $this->io->progressAdvance();
                    $i++;
                }
                $this->clearEntityManager();
            }
            $this->commitTransaction();
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
        $this->io->progressFinish();
        $this->io->table(
            ['#', 'File', 'Title', 'Version', 'Validity'],
            $results
        );
        $this->io->success('Imported ' . $documentCount . ' documents.');

        return 0;
    }

    /**
     * @param \DateTimeImmutable|null $validFrom
     * @param \DateTimeImmutable|null $validUntil
     * @return string
     */
    private static function formatValidity(?\DateTimeImmutable $validFrom, ?\DateTimeImmutable $validUntil): string
    {
        if ($validFrom === null && $validUntil === null) {
            return '∞';
        }
        $str = '';
        if ($validFrom !== null) {
            $str .= $validFrom->format('F j, Y');
        } else {
            $str .= '∞';
        }
        $str .= ' – ';
        if ($validUntil !== null) {
            $str .= $validUntil->format('F j, Y');
        } else {
            $str .= '∞';
        }
        return $str;
    }
}
