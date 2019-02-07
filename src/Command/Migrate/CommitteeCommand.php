<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-04
 * Time: 07:15
 */

namespace App\Command\Migrate;

use App\Application\Committee\Command\CreateCommittee;
use App\Application\Committee\Command\CreateCommitteeMember;
use App\Domain\Common\Country;
use App\Domain\Model\Committee\CommitteeMember;
use App\Utils\Validation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

/**
 * Class CommitteeCommand
 *
 * @package App\Command\Migrate
 */
class CommitteeCommand extends CommandWithFilesystem
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('app:migrate:committee')
            ->setDescription('Migrates committees from legacy database.')
            ->setHelp('This command allows you to migrate committees from a IISHF legacy database.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $this->io->title('Migrate committees rom legacy database');


        $templateFile = $this->legacyPath . '/www/wwwroot/templates/disciplinary.tpl';
        if (!self::isFileReadable($templateFile)) {
            $this->io->warning(
                'Legacy committee template file ' . $templateFile . ' does not exist or is not readable.'
            );
            return 1;
        }
        $this->io->comment('Full legacy committee template file ' . $templateFile);

        $xml        = $this->loadCommitteeTemplate($templateFile);
        $committees = $this->extractCommittees($xml);

        $results = [];
        $this->io->section('Importing documents...');
        $this->io->progressStart(count($committees));
        $this->beginTransaction();
        try {
            foreach ($committees as $i => $committee) {
                $createCommittee = CreateCommittee::create();
                $createCommittee->setTitle($committee['title']);

                try {
                    $this->dispatchCommand($createCommittee);
                    foreach ($committee['members'] as $member) {
                        $createMember = CreateCommitteeMember::create($createCommittee->getId());
                        $createMember->setFirstName($member['firstName'])
                                     ->setLastName($member['lastName'])
                                     ->setCountry($member['country'])
                                     ->setTitle($member['title']);

                        if (in_array($member['title'], ['Chairman', 'Disciplinary Officer'])) {
                            $createMember->setMemberType(CommitteeMember::MEMBER_TYPE_CHAIRMAN);
                        } elseif (in_array($member['title'], ['Vice-Chairman', 'Vice Disciplinary Officer'])) {
                            $createMember->setMemberType(CommitteeMember::MEMBER_TYPE_VICE_CHAIRMAN);
                        } else {
                            $createMember->setMemberType(CommitteeMember::MEMBER_TYPE_MEMBER);
                        }

                        if ($committee['title'] === 'IISHF Disciplinary Committee') {
                            $createMember->setTermType(CommitteeMember::TERM_TYPE_NOMINATED_IISHF)
                                         ->setTermSince(2017)
                                         ->setTermDuration(2);

                        } elseif ($committee['title'] === 'IISHF Appeals Committee') {
                            $createMember->setTermType(CommitteeMember::TERM_TYPE_ELECTED)
                                         ->setTermSince(2017)
                                         ->setTermDuration(2);

                        } elseif ($committee['title'] === 'IISHF Technical Committee') {
                            if (in_array($member['title'], ['Chairman', 'Vice-Chairman'])) {
                                $createMember->setTermType(CommitteeMember::TERM_TYPE_ELECTED)
                                             ->setTermSince(2017)
                                             ->setTermDuration(2);
                            } else {
                                $createMember->setTermType(CommitteeMember::TERM_TYPE_NOMINATED_NGB);
                            }
                        }

                        $this->dispatchCommand($createMember);
                    }
                    $result = '';
                } catch (ValidationFailedException $e) {
                    $result = implode(PHP_EOL, Validation::getViolations($e));
                } catch (\Throwable $e) {
                    $result = $e->getMessage();
                }

                $results[] = [
                    $i + 1,
                    $createCommittee->getTitle(),
                    count($committee['members']),
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
            ['#', 'Title', 'Members'],
            $results
        );
        $this->io->success('Imported ' . count($committees) . ' committees.');

        return 0;
    }

    /**
     * @param string $templateFile
     * @return \SimpleXMLElement
     */
    private function loadCommitteeTemplate(string $templateFile): \SimpleXMLElement
    {
        $content = file_get_contents($templateFile);
        $dom     = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTML(
            <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
$content
</body>
</html>
HTML
        );
        return simplexml_import_dom($dom->documentElement);
    }

    /**
     * @param \SimpleXMLElement $xml
     * @return array
     */
    private function extractCommittees(\SimpleXMLElement $xml): array
    {
        $countries  = array_flip(Country::getCountries());
        $committees = [];
        foreach ($xml->xpath('//div[@class="data"]/h2') as $c) {
            /** @var \SimpleXMLElement $c */

            $members = [];
            foreach ($c->xpath('following-sibling::ul[1]/li') as $m) {
                [$name, $country, $title] = array_map('trim', explode(',', (string)$m));
                [$firstName, $lastName] = explode(' ', $name);

                if ($country === 'Great Britain') {
                    $country = 'United Kingdom';
                }
                if (!isset($countries[$country])) {
                    throw new \OutOfBoundsException('Country ' . $country . ' is unknown.');
                }

                $members[] = [
                    'firstName' => $firstName,
                    'lastName'  => $lastName,
                    'country'   => $countries[$country],
                    'title'     => rtrim($title, ' (*)'),
                ];
            }

            $committees[] = [
                'title'   => (string)$c,
                'members' => $members,
            ];
        }
        return $committees;
    }
}
