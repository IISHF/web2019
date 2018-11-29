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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

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
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email Address')
            ->addOption('role', 'r', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Role(s)')
            ->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'Password', false);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Create a new user');

        $firstName = $input->getOption('first-name');
        if (!$firstName) {
            $firstName = $io->ask('First Name', 'First');
        }
        $lastName = $input->getOption('last-name');
        if (!$lastName) {
            $lastName = $io->ask('Last Name', 'Last');
        }
        $email = $input->getOption('email');
        if (!$email) {
            $email = $io->ask('Email Address', 'first.last@test.com');
        }
        $roles = $input->getOption('role');
        if (empty($roles)) {
            $rolesQuestion = new ChoiceQuestion('Role(s)', ['none', 'ROLE_ADMIN'], null);
            $rolesQuestion->setMultiselect(true);
            $roles = array_filter(
                $io->askQuestion($rolesQuestion),
                function (string $r) {
                    return $r !== 'none';
                }
            );
        }

        $password     = $input->getOption('password');
        $withPassword = ($password !== false);
        if ($withPassword && $password === null) {
            $password = $io->askHidden('Password');
            if ($password === null) {
                $withPassword = false;
            }
        }

        $io->section('User Details');
        $io->table(
            [],
            [
                ['First Name', $firstName],
                ['Last Name', $lastName],
                ['Email Address', $email],
                ['Roles', !empty($roles) ? implode(', ', $roles) : 'none'],
                ['Password', $withPassword ? 'yes' : 'no'],
            ]
        );

        if (!$io->confirm('Create new user?')) {
            $io->note('Aborted.');
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
            $io->error(
                array_merge(
                    ['Validation failed.'],
                    array_map(
                        function (ConstraintViolationInterface $violation) {
                            return $violation->getPropertyPath() . ': ' . $violation->getMessage();
                        },
                        iterator_to_array($e->getViolations())
                    )
                )
            );
            return 1;
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            return 1;
        }

        if ($withPassword) {
            $io->success('User ' . $email . ' created.');
        } else {
            $io->success(
                [
                    'User ' . $email . ' created.',
                    'Use token ' . $createUser->getConfirmToken() . ' to confirm user.',
                ]
            );
        }

        return 0;
    }
}
