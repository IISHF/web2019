<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 11:20
 */

namespace App\Command\User;

use App\Domain\Model\User\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DetailsCommand
 *
 * @package App\Command\User
 */
class DetailsCommand extends Command
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:user:details')
            ->setDescription('Shows user details.')
            ->setHelp('This command allows you to show user details.')
            ->addArgument('email', InputArgument::REQUIRED, 'E-mail');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Show user details');

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
                ['E-mail', $user->getEmail()],
                ['Roles', !empty($user->getRoles()) ? implode(', ', $user->getRoles()) : 'none'],
                ['Confirmed', $user->isConfirmed() ? 'yes' : 'no'],
            ]
        );

        return 0;
    }
}
