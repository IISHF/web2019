<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 17:29
 */

namespace App\Command\Migrate;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class CommandWithFilesystem
 *
 * @package App\Command\Migrate
 */
abstract class CommandWithFilesystem extends Command
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var string
     */
    private $currentDirectory;

    /**
     * @var string
     */
    protected $legacyPath;

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();

        $this->fs               = new Filesystem();
        $this->currentDirectory = getcwd();
        $legacyDefaultPath      = $this->fs->makePathRelative(
            __DIR__ . '/../../../../website',
            $this->currentDirectory
        );

        $this->addOption(
            'legacy-path',
            'p',
            InputOption::VALUE_REQUIRED,
            'Path to legacy website',
            $legacyDefaultPath
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $legacyPath = $input->getOption('legacy-path');
        $this->io->note('Using legacy website path ' . $legacyPath);
        $legacyPathFull = $legacyPath;
        if ($legacyPathFull[0] !== '/') {
            $legacyPathFull = $this->currentDirectory . '/' . $legacyPathFull;
            $this->io->comment('Full legacy website path ' . $legacyPathFull);
        }
        $this->legacyPath = $legacyPathFull;
    }

    /**
     * @param string $path
     * @return \SplFileInfo|null
     */
    protected static function getFile(string $path): ?\SplFileInfo
    {
        if (file_exists($path) && is_file($path) && is_readable($path)) {
            return new \SplFileInfo($path);
        }
        return null;
    }

    /**
     * @param string $path
     * @return bool
     */
    protected static function isDirectoryReadable(string $path): bool
    {
        return realpath($path) && is_dir($path) && is_readable($path . '/.');
    }
}
