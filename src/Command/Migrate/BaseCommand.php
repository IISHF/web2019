<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 14:42
 */

namespace App\Command\Migrate;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class BaseCommand
 *
 * @package App\Command\Migrate
 */
abstract class BaseCommand extends Command
{
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $db;

    /**
     * @param MessageBusInterface    $commandBus
     * @param EntityManagerInterface $em
     */
    public function __construct(MessageBusInterface $commandBus, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
        $this->em         = $em;
    }

    /**
     * @param object $message
     * @return mixed
     */
    protected function dispatchCommand(object $message)
    {
        return $this->commandBus->dispatch($message);
    }

    /**
     *
     */
    protected function beginTransaction(): void
    {
        $this->em->getConnection()->beginTransaction();
    }

    /**
     *
     */
    protected function commitTransaction(): void
    {
        $this->em->getConnection()->commit();
    }

    /**
     *
     */
    protected function rollBackTransaction(): void
    {
        $this->em->getConnection()->rollBack();
    }

    /**
     *
     */
    protected function clearEntityManager(): void
    {
        $this->em->clear();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->addOption(
            'db',
            null,
            InputOption::VALUE_REQUIRED,
            'The legacy database URI',
            'mysql://iishf:iishf@127.0.0.1/iishf_org'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);

        $dbUri    = $input->getOption('db');
        $this->db = \Doctrine\DBAL\DriverManager::getConnection(['url' => $dbUri]);
    }
}
