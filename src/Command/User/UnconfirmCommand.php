<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 10:57
 */

namespace App\Command\User;

use App\Application\User\Command\UnconfirmUser;
use App\Domain\Model\User\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class UnconfirmCommand
 *
 * @package App\Command\User
 */
class UnconfirmCommand extends Command
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
            ->setName('app:user:unconfirm')
            ->setDescription('Marks a user as unconfirmed.')
            ->setHelp('This command allows you to mark a user as unconfirmed.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email Address');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Mark a user as unconfirmed');

        $email = $input->getArgument('email');
        $user  = $this->userRepository->findByEmail($email);
        if (!$user) {
            $io->error('User ' . $email . ' not found.');
            return 1;
        }
        if (!$user->isConfirmed()) {
            $io->error('User ' . $user->getEmail() . ' is not confirmed.');
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

        if (!$io->confirm('Mark user ' . $user->getEmail() . ' as unconfirmed?')) {
            $io->note('Aborted.');
            return 130;
        }

        $confirmUser = UnconfirmUser::unconfirm($email);

        try {
            $this->commandBus->dispatch($confirmUser);
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            return 1;
        }

        $io->success(
            [
                'User ' . $user->getEmail() . ' marked as unconfirmed.',
                'Use token ' . $confirmUser->getConfirmToken() . ' to confirm user.',
            ]
        );

        return 0;
    }
}
