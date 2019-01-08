<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 14:14
 */

namespace App\Command\Migrate;

use App\Application\User\Command\CreateUser;
use App\Utils\Validation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

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
        $this->beginTransaction();
        try {
            foreach ($users as $i => $user) {
                $createUser = CreateUser::create()
                                        ->setEmail($user['userid'])
                                        ->setFirstName($user['surname'])
                                        ->setLastName($user['name']);

                $result = $createUser->getConfirmToken();
                try {
                    $this->dispatchCommand($createUser);
                } catch (ValidationFailedException $e) {
                    $result = implode(PHP_EOL, Validation::getViolations($e));
                } catch (\Throwable $e) {
                    $result = $e->getMessage();
                }

                $results[] = [
                    $i + 1,
                    $createUser->getEmail(),
                    $createUser->getFirstName(),
                    $createUser->getLastName(),
                    $result,
                ];
                $this->io->progressAdvance();

                $this->clearEntityManager();
            }
            $this->commitTransaction();
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
        $this->io->progressFinish();
        $this->io->table(
            ['#', 'E-mail', 'First Name', 'Last Name', 'Confirmation Token'],
            $results
        );

        return 0;
    }
}
