<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 10:28
 */

namespace App\Command\File;

use App\Command\Command;
use App\Domain\Model\File\FileRepository;
use App\Utils\Number;
use App\Utils\Text;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 *
 * @package App\Command\File
 */
class ListCommand extends Command
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
            ->setName('app:file:list')
            ->setDescription('List all files.')
            ->setHelp('This command allows you to list all files.')
            ->addOption('type', 't', InputOption::VALUE_REQUIRED, 'File Type (MIME)')
            ->addOption('origin', 'o', InputOption::VALUE_REQUIRED, 'File Origin (com.iishf.*)');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $this->io->title('File list');

        $mimeType = $input->getOption('type');
        $origin   = $input->getOption('origin');

        $rows        = [];
        $binarySizes = [];
        $virtualSize = 0;
        foreach ($this->fileRepository->findAll($mimeType, $origin) as $i => $file) {
            $hash     = $file->getBinary()->getHash();
            $fileSize = $file->getSize();
            [$size, $unit] = Number::fileSize($fileSize);
            $rows[]             = [
                $i + 1,
                Text::shorten($file->getName(), 20),
                Text::shorten($file->getOriginalName() ?? '', 20),
                Text::shorten($file->getOrigin(), 20),
                Text::shorten($file->getMimeType(), 20),
                number_format($size, 1) . ' ' . $unit,
                Text::shorten($hash, 20),
                $file->getCreatedAt()->format('F j, Y H:i'),
            ];
            $binarySizes[$hash] = $fileSize;
            $virtualSize        += $fileSize;
        }

        $this->io->table(
            ['#', 'Name', 'Original Name', 'Origin', 'Type', 'Size', 'Binary', 'Created'],
            $rows
        );

        $physicalSize = array_sum($binarySizes);
        [$vSize, $vUnit] = Number::fileSize($virtualSize);
        [$pSize, $pUnit] = Number::fileSize($physicalSize);
        $this->io->listing(
            [
                'Virtual size:  ' . number_format($vSize, 1) . ' ' . $vUnit,
                'Physical size: ' . number_format($pSize, 1) . ' ' . $pUnit,
                'Saving:        ' . number_format((1 - ($physicalSize / $virtualSize)) * 100, 1) . '%,',
            ]
        );

        return 0;
    }
}
