<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 15:58
 */

namespace App\Command\User;

use App\Application\User\Command\CreateConfirmedUser;
use App\Application\User\Command\CreateUser;
use App\Command\Command;
use App\Utils\Validation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * Class CreateCommand
 *
 * @package App\Command\User
 */
class CreateCommand extends Command
{
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @param MessageBusInterface $commandBus
     */
    public function __construct(MessageBusInterface $commandBus)
    {
        parent::__construct();
        $this->commandBus = $commandBus;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:user:create')
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a new user.')
            ->addOption('first-name', 'fn', InputOption::VALUE_REQUIRED, 'First Name')
            ->addOption('last-name', 'ln', InputOption::VALUE_REQUIRED, 'Last Name')
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'E-mail')
            ->addOption('role', 'r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Role(s)')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'Password', false);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Create a new user');

        $firstName = $input->getOption('first-name');
        if (!$firstName) {
            $firstName = $this->io->ask('First Name', 'First');
        }
        $lastName = $input->getOption('last-name');
        if (!$lastName) {
            $lastName = $this->io->ask('Last Name', 'Last');
        }
        $email = $input->getOption('email');
        if (!$email) {
            $email = $this->io->ask('E-mail', 'first.last@test.com');
        }
        $roles = $input->getOption('role');
        if (empty($roles)) {
            $rolesQuestion = new ChoiceQuestion('Role(s)', ['none', 'ROLE_ADMIN'], null);
            $rolesQuestion->setMultiselect(true);
            $roles = array_filter(
                $this->io->askQuestion($rolesQuestion),
                function (string $r) {
                    return $r !== 'none';
                }
            );
        }

        $password     = $input->getOption('password');
        $withPassword = ($password !== false);
        if ($withPassword && $password === null) {
            $password = $this->io->askHidden('Password');
            if ($password === null) {
                $withPassword = false;
            }
        }

        $this->io->section('User Details');
        $this->io->table(
            [],
            [
                ['First Name', $firstName],
                ['Last Name', $lastName],
                ['E-mail', $email],
                ['Roles', !empty($roles) ? implode(', ', $roles) : 'none'],
                ['Password', $withPassword ? 'yes' : 'no'],
            ]
        );

        if (!$this->io->confirm('Create new user?')) {
            $this->io->note('Aborted.');
            return 130;
        }

        if ($withPassword) {
            $createUser = CreateConfirmedUser::create()
                                             ->setPassword($password);
        } else {
            $createUser = CreateUser::create();
        }

        $createUser->setFirstName($firstName)
                   ->setLastName($lastName)
                   ->setEmail($email)
                   ->setRoles($roles);

        try {
            $this->commandBus->dispatch($createUser);
        } catch (ValidationFailedException $e) {
            $this->io->error(array_merge(['Validation failed.'], Validation::getViolations($e)));
            return 1;
        } catch (Throwable $e) {
            $this->io->error($e->getMessage());
            return 1;
        }

        if ($withPassword) {
            $this->io->success('User ' . $email . ' created.');
        } else {
            $this->io->success(
                [
                    'User ' . $email . ' created.',
                    'Use token ' . $createUser->getConfirmToken() . ' to confirm user.',
                ]
            );
        }

        return 0;
    }
}
