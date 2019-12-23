<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 10:57
 */

namespace App\Command\User;

use App\Application\User\Command\UnconfirmUser;
use App\Command\Command;
use App\Domain\Model\User\UserRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

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
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Mark a user as unconfirmed');

        $email = $input->getArgument('email');
        $user  = $this->userRepository->findByEmail($email);
        if (!$user) {
            $this->io->error('User ' . $email . ' not found.');
            return 1;
        }
        if (!$user->isConfirmed()) {
            $this->io->error('User ' . $user->getEmail() . ' is not confirmed.');
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

        if (!$this->io->confirm('Mark user ' . $user->getEmail() . ' as unconfirmed?')) {
            $this->io->note('Aborted.');
            return 130;
        }

        $confirmUser = UnconfirmUser::unconfirm($email);

        try {
            $this->commandBus->dispatch($confirmUser);
        } catch (Throwable $e) {
            $this->io->error($e->getMessage());
            return 1;
        }

        $this->io->success(
            [
                'User ' . $user->getEmail() . ' marked as unconfirmed.',
                'Use token ' . $confirmUser->getConfirmToken() . ' to confirm user.',
            ]
        );

        return 0;
    }
}
