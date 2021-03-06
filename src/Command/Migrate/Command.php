<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 14:42
 */

namespace App\Command\Migrate;

use App\Command\Command as BaseCommand;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class Command
 *
 * @package App\Command\Migrate
 */
abstract class Command extends BaseCommand
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
     * @var Connection
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
        $this->em->beginTransaction();
    }

    /**
     *
     */
    protected function commitTransaction(): void
    {
        if ($this->em->getConnection()->isRollbackOnly()) {
            $this->em->rollback();
        } else {
            $this->em->commit();
        }
    }

    /**
     *
     */
    protected function rollbackTransaction(): void
    {
        $this->em->rollback();
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
        parent::initialize($input, $output);

        $dbUri    = $input->getOption('db');
        $this->db = DriverManager::getConnection(['url' => $dbUri]);
    }

    /**
     * @param string $query
     * @param string $keyColumn
     * @param string $valueColumn
     * @return array
     */
    protected function createLookupMap(string $query, string $keyColumn = 'id', string $valueColumn = 'name'): array
    {
        $map = [];
        foreach ($this->db->fetchAll($query) as $i) {
            $map[(int)$i[$keyColumn]] = $i[$valueColumn];
        }
        return $map;
    }
}
