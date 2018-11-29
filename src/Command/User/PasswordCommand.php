<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 10:47
 */

namespace App\Command\User;

use App\Application\User\Command\SetPassword;
use App\Domain\Model\User\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class PasswordCommand
 *
 * @package App\Command\User
 */
class PasswordCommand extends Command
{
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param MessageBusInterface $commandBus
     * @param UserRepository      $userRepository
     */
    public function __construct(MessageBusInterface $commandBus, UserRepository $userRepository)
    {
        parent::__construct();
        $this->commandBus     = $commandBus;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:user:password')
            ->setDescription('Confirms a user.')
            ->setHelp('This command allows you to confirm a user.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email Address')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Password');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Set user password');

        $email = $input->getArgument('email');
        $user  = $this->userRepository->findByEmail($email);
        if (!$user) {
            $io->error('User ' . $email . ' not found.');
            return 1;
        }

        $io->section('User Details');
        $io->table(
            [],
            [
                ['First Name', $user->getFirstName()],
                ['Last Name', $user->getLastName()],
                ['Email Address', $user->getEmail()],
                ['Roles', !empty($user->getRoles()) ? implode(', ', $user->getRoles()) : 'none'],
            ]
        );

        $password = $input->getOption('password');
        if ($password === null) {
            $password = $io->askHidden('Password');
            if ($password === null) {
                $io->error('A password is required to confirm a user.');
                return 1;
            }
        }

        if (!$io->confirm('Set password for user ' . $user->getEmail() . '?')) {
            $io->note('Aborted.');
            return 130;
        }

        $setPassword = SetPassword::set($user->getEmail())
                                  ->setPassword($password);

        try {
            $this->commandBus->dispatch($setPassword);
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            return 1;
        }

        $io->success('Password changed for user ' . $user->getEmail() . '.');

        return 0;
    }
}
