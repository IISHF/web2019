<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 11:10
 */

namespace App\Command\File;

use App\Command\Command;
use App\Domain\Model\File\FileBinary;
use App\Domain\Model\File\FileRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BinariesCommand
 *
 * @package App\Command\File
 */
class BinariesCommand extends Command
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
            ->setName('app:file:binaries')
            ->setDescription('List all file binaries.')
            ->setHelp('This command allows you to list all file binaries and optionally remove unused binaries.')
            ->addOption('only-unused', null, InputOption::VALUE_NONE, 'Only show unused binaries')
            ->addOption('remove-unused', null, InputOption::VALUE_NONE, 'Remove unused binaries')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force removal of unused binaries');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $onlyUnused   = $input->getOption('only-unused');
        $removeUnused = $input->getOption('remove-unused');
        $forceRemoval = $input->getOption('force');
        if ($onlyUnused) {
            $this->io->title('Unused file binary list');
        } else {
            $this->io->title('File binary list');
        }

        $rows             = [];
        $binariesToRemove = [];
        foreach ($this->fileRepository->findAllBinaries($onlyUnused) as $i => [$binary, $count]) {
            /** @var FileBinary $binary */
            $rows[] = [
                $i + 1,
                $binary->getHash(),
                $binary->getCreatedAt()->format('F j, Y H:i'),
                sprintf('<%2$s>%d</%2$s>', $count, $count > 0 ? 'info' : 'error'),
            ];
            if ($removeUnused && $count < 1) {
                $binariesToRemove[] = $binary->getHash();
            }
        }

        if (empty($rows)) {
            if ($onlyUnused) {
                $this->io->note('No unused file binaries found.');
            } else {
                $this->io->note('No file binaries found.');
            }
            return 0;
        }
        $this->io->table(
            ['#', 'Hash', 'Created', 'Usage Count'],
            $rows
        );
        if ($removeUnused) {
            if (count($binariesToRemove) > 0) {
                $this->io->caution('The following binaries will be removed:');
                $this->io->listing($binariesToRemove);
                if ($forceRemoval || $this->io->confirm('Do you really want to continue?', false)) {
                    $removed = $this->fileRepository->deleteBinariesByHash(...$binariesToRemove);
                    $this->io->success($removed . ' unused binaries have been removed.');
                } else {
                    $this->io->note('Removal of unused binaries has not been confirmed and will be aborted.');
                }
            } else {
                $this->io->note('No unused file binaries found to be removed.');
            }
        }

        return 0;
    }
}
