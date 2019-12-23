<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 10:34
 */

namespace App\Command\User;

use App\Application\User\Command\ConfirmUser;
use App\Command\Command;
use App\Domain\Model\User\UserRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

/**
 * Class ConfirmCommand
 *
 * @package App\Command\User
 */
class ConfirmCommand extends Command
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
            ->setName('app:user:confirm')
            ->setDescription('Confirms a user.')
            ->setHelp('This command allows you to confirm a user.')
            ->addArgument('token', InputArgument::REQUIRED, 'Confirmation Token')
            ->addOption('password', 'p', InputOption::VALUE_REQUIRED, 'Password');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Confirm a user');

        $confirmationToken = $input->getArgument('token');
        $user              = $this->userRepository->findByConfirmToken($confirmationToken);
        if (!$user) {
            $this->io->error('User not found for confirmation token ' . $confirmationToken . '.');
            return 1;
        }
        if ($user->isConfirmed()) {
            $this->io->error('User ' . $user->getEmail() . ' is already confirmed.');
            return 1;
        }

        $this->io->section('User Details');
        $this->io->table(
            [],
            [
                ['First Name', $user->getFirstName()],
                ['Last Name', $user->getLastName()],
                ['E-mail', $user->getEmail()],
                ['Roles', !empty($user->getRoles()) ? implode(', ', $user->getRoles()) : 'none'],
            ]
        );

        $password = $input->getOption('password');
        if ($password === null) {
            $password = $this->io->askHidden('Password');
            if ($password === null) {
                $this->io->error('A password is required to confirm a user.');
                return 1;
            }
        }

        if (!$this->io->confirm('Confirm user ' . $user->getEmail() . '?')) {
            $this->io->note('Aborted.');
            return 130;
        }

        $confirmUser = ConfirmUser::confirm($confirmationToken)
                                  ->setPassword($password);

        try {
            $this->commandBus->dispatch($confirmUser);
        } catch (Throwable $e) {
            $this->io->error($e->getMessage());
            return 1;
        }

        $this->io->success('User ' . $user->getEmail() . ' confirmed.');

        return 0;
    }
}
