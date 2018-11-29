<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 14:14
 */

namespace App\Command\Migrate;

use App\Application\User\Command\CreateUser;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class UserCommand
 *
 * @package App\Command\Migrate
 */
class UserCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('app:migrate:user')
            ->setDescription('Migrates users from legacy database.')
            ->setHelp('This command allows you to migrate users from a IISHF legacy database.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $this->io->title('Migrate users rom legacy database');

        $users = $this->db->fetchAll('SELECT userid, surname, name FROM sys_users');
        $this->io->progressStart(\count($users));
        $results = [];
        foreach ($users as $user) {
            $createUser = CreateUser::create()
                                    ->setEmail($user['userid'])
                                    ->setFirstName($user['surname'])
                                    ->setLastName($user['name']);

            $result = $createUser->getConfirmToken();
            try {
                $this->dispatchCommand($createUser);
            } catch (\Throwable $e) {
                $result = $e->getMessage();
            }

            $results[] = [
                $createUser->getEmail(),
                $createUser->getFirstName(),
                $createUser->getLastName(),
                $result,
            ];
            $this->io->progressAdvance();
        }
        $this->io->progressFinish();
        $this->io->table(
            ['Email', 'First Name', 'Last Name', 'Confirmation Token'],
            $results
        );

        return 0;
    }
}
