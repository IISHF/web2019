<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 12:10
 */

namespace App\Command\File;

use App\Command\Command;
use App\Domain\Model\File\FileRepository;
use App\Utils\Number;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FileCommand
 *
 * @package App\Command\File
 */
class FileCommand extends Command
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
        parent::__construct();
        $this->fileRepository = $fileRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:file:file')
            ->setDescription('Show details on a file.')
            ->setHelp('This command allows you to show details on a file and optionally save the file.')
            ->addArgument('file', InputArgument::REQUIRED, 'The file id')
            ->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'The output filename', false)
            ->addOption('stream', 's', InputOption::VALUE_NONE, 'Output the file to stdout');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $fileId = $input->getArgument('file');

        $outputFile = $input->getOption('output') ?? true;
        $stdOut     = ($outputFile === true) ? $input->getOption('stream') : false;

        $file = $this->fileRepository->findById($fileId);
        if (!$file) {
            if (!$stdOut) {
                $this->io->error('File ' . $fileId . ' not found.');
            }
            return 1;
        }

        if (!$stdOut) {
            $this->io->title('Details on file ' . $fileId);
            [$size, $unit] = Number::fileSize($file->getSize());
            $this->io->table(
                [],
                [
                    ['ID', $file->getId()],
                    ['Name', $file->getName()],
                    ['Original Name', $file->getOriginalName()],
                    ['Origin', $file->getOrigin()],
                    ['Created', $file->getCreatedAt()->format('F j, Y H:i')],
                    new TableSeparator(),
                    ['Size', number_format($size, 1) . ' ' . $unit],
                    ['Type', $file->getMimeType()],
                    ['Hash', $file->getBinary()->getHash()],
                    new TableSeparator(),
                    ['Client Name', $file->getClientName()],
                    ['Safe Client Name', $file->getSafeClientName()],
                ]
            );
            if ($outputFile !== false) {
                if ($outputFile === true) {
                    $outputFile = $file->getClientName();
                }
                $this->io->text('Saving file   ' . $fileId . ' to ' . $outputFile . '...');
                $file->writeTo($outputFile);
                $this->io->text('done.');
                $this->io->success('File  ' . $fileId . ' saved to ' . $outputFile . '.');
            }
        } else {
            file_put_contents('php://stdout', $file->getBinary()->getData());
        }

        return 0;
    }
}
