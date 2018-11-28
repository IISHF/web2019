<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 15:58
 */

namespace App\Command;

use App\Application\User\ConfirmUser;
use App\Application\User\CreateUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class CreateAdminCommand
 *
 * @package App\Command
 */
class CreateAdminCommand extends Command
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
            ->setName('app:create-admin')
            ->setDescription('Creates a new admin.')
            ->setHelp('This command allows you to create an admin.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $createUser = CreateUser::create();
        $createUser->setFirstName('John');
        $createUser->setLastName('Doe');
        $createUser->setEmail('test@test.de');
        $createUser->setRoles(['ROLE_ADMIN']);
        $this->commandBus->dispatch($createUser);

        $confirmUser = ConfirmUser::confirm($createUser->getConfirmToken());
        $confirmUser->setNewPassword('admin');
        $this->commandBus->dispatch($confirmUser);

        return 0;
    }


}
