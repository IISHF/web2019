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
class DocumentCommand extends BaseCommandWithFilesystem
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
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $this->io->title('Migrate documents rom legacy database');


        $documentsPath = $this->legacyPath . '/www/wwwroot/regulations';
        if (!self::isDirectoryReadable($documentsPath)) {
            $this->io->warning('Legacy document path ' . $documentsPath . ' does not exist or is not readable.');
            return 1;
        }
        $this->io->comment('Full legacy document path ' . $documentsPath);

        $titles = [
            'rulesofthegame'    => 'Official Rule Book',
            'regulations'       => 'Regulations',
            'constitution'      => 'Constitution',
            'itc'               => 'International Team Certification',
            'sanctioning'       => 'Sanctioning Workflow for Non Title Events',
            'application'       => 'Candidature Form for IISHF Non Title Event (B-Event)',
            'hosting-te'        => 'Guideline for hosting of Title Events',
            'sanctioning-te'    => 'Sanctioning Workflow for Title Events',
            'application-te'    => 'Candidature Form for IISHF Title Event (A-Event)',
            'checklist-host-te' => 'Title Event Checklist for the Host',
            'participating-te'  => 'Guidelines for teams participating at Title Events',
            'deadlines-iishf'   => 'IISHF Deadlines',
            'disciplinary'      => 'Disciplinary Regulations',
            'financial'         => 'Financial Regulations',
            'tournaments'       => 'Tournament Regulations',
        ];

        $it            = new \GlobIterator(
            $documentsPath . '/*.*',
            \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_FILEINFO | \FilesystemIterator::SKIP_DOTS
        );
        $documents     = [];
        $documentCount = 0;
        foreach ($it as $file) {
            /** @var \SplFileInfo $file */
            $matches = [];
            if (preg_match('/^([\w-]+)\.(\d{4})\.\w+$/', $file->getFilename(), $matches)
                && isset($titles[$matches[1]])
            ) {
                $documents[$matches[1]][$matches[2]] = $file;
                $documentCount++;
            }
        }

        array_walk(
            $documents,
            function (array &$d) {
                ksort($d, SORT_NUMERIC);
            }
        );
        ksort($documents, SORT_STRING);

        $this->io->progressStart($documentCount);
        $this->beginTransaction();
        try {
            foreach ($documents as $type => $typeDocuments) {
                /** @var bool|string $createDoc */
                $createDoc = true;
                foreach ($typeDocuments as $year => $typeDocument) {
                    if ($createDoc === true) {
                        $createDocument = CreateDocument::create();
                        $createDocument->setTitle($titles[$type])
                                       ->setTags([$type])
                                       ->setVersion('Season ' . $year)
                                       ->setValidFrom(new \DateTimeImmutable($year . '-01-01'))
                                       ->setValidUntil(new \DateTimeImmutable($year . '-12-31'))
                                       ->setFile($typeDocument);

                        $this->dispatchCommand($createDocument);

                        $createDoc = $createDocument->getId();
                    } else {
                        $createVersion = CreateDocumentVersion::create($createDoc);
                        $createVersion->setVersion('Season ' . $year)
                                      ->setValidFrom(new \DateTimeImmutable($year . '-01-01'))
                                      ->setValidUntil(new \DateTimeImmutable($year . '-12-31'))
                                      ->setFile($typeDocument);

                        $this->dispatchCommand($createVersion);
                    }

                    $this->io->progressAdvance();
                }
                $this->clearEntityManager();
            }
            $this->commitTransaction();
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
        $this->io->progressFinish();

        return 0;
    }
}
