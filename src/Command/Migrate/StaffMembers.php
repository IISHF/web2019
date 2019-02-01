<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 17:16
 */

namespace App\Command\Migrate;

use App\Application\Staff\Command\CreateStaffMember;
use App\Utils\Validation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

/**
 * Class StaffMembers
 *
 * @package App\Command\Migrate
 */
class StaffMembers extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('app:migrate:staff')
            ->setDescription('Migrates staff members from legacy database.')
            ->setHelp('This command allows you to migrate staff members from a IISHF legacy database.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $this->io->title('Migrate staff members rom legacy database');

        $titles = $this->createLookupMap('SELECT mid, description FROM iishf_chart', 'mid', 'description');

        $staffMembers = $this->db->fetchAll('SELECT id, name, surname, email FROM iishf_staff');
        $this->io->progressStart(\count($staffMembers));
        $results = [];
        $this->beginTransaction();
        try {
            foreach ($staffMembers as $i => $member) {
                $createMember = CreateStaffMember::create()
                                                 ->setFirstName($member['name'])
                                                 ->setLastName($member['surname'])
                                                 ->setEmail($member['email'])
                                                 ->setTitle($titles[$member['id']] ?? 'unknown');

                try {
                    $this->dispatchCommand($createMember);
                    $result = '';
                } catch (ValidationFailedException $e) {
                    $result = implode(PHP_EOL, Validation::getViolations($e));
                } catch (\Throwable $e) {
                    $result = $e->getMessage();
                }

                $results[] = [
                    $i + 1,
                    $createMember->getTitle(),
                    $createMember->getFirstName(),
                    $createMember->getLastName(),
                    $createMember->getEmail(),
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
            ['#', 'Title', 'First Name', 'Last Name', 'E-mail'],
            $results
        );

        return 0;
    }
}
