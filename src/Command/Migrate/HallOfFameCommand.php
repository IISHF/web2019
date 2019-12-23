<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:26
 */

namespace App\Command\Migrate;

use App\Application\HallOfFame\Command\CreateHallOfFameEntry;
use App\Domain\Common\AgeGroup;
use App\Utils\Validation;
use Exception;
use SimpleXMLElement;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Throwable;
use Traversable;

/**
 * Class HallOfFameCommand
 *
 * @package App\Command\Migrate
 */
class HallOfFameCommand extends CommandWithFilesystem
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('app:migrate:hall_of_fame')
            ->setDescription('Migrates hall of fame from legacy database.')
            ->setHelp('This command allows you to migrate the hall of fame from a IISHF legacy database.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io->title('Migrate hall of fame rom legacy database');


        $dataFile = $this->legacyPath . '/www/wwwroot/events/archiv/hallOfFame.xml';
        if (!self::isFileReadable($dataFile)) {
            $this->io->warning(
                'Legacy hall of fame file ' . $dataFile . ' does not exist or is not readable.'
            );
            return 1;
        }
        $this->io->comment('Full legacy hall of fame file ' . $dataFile);

        $results = [];
        $count   = 0;
        $this->io->section('Importing hall of fame...');
        $this->io->progressStart();
        $this->beginTransaction();
        try {
            foreach ($this->readDataFile($dataFile) as $i => $entry) {
                if ($entry['championship']) {
                    $createEntry = CreateHallOfFameEntry::createEuropeanChampionship($entry['ageGroup']);
                } elseif ($entry['ageGroup'] === 'Men Cup Winners') {
                    $createEntry = CreateHallOfFameEntry::createEuropeanCupWinnersCup(AgeGroup::AGE_GROUP_MEN);
                } else {
                    $createEntry = CreateHallOfFameEntry::createEuropeanCup($entry['ageGroup']);
                }
                $createEntry->setSeason($entry['season'])
                            ->setEventDate($entry['eventDate'])
                            ->setWinnerClub($entry['winnerClub'])
                            ->setWinnerCountry($entry['winnerCountry'])
                            ->setHostClub($entry['hostClub'])
                            ->setHostCountry($entry['hostCountry']);

                try {
                    $this->dispatchCommand($createEntry);
                    $result = '';
                    $count++;
                } catch (ValidationFailedException $e) {
                    $result = implode(PHP_EOL, Validation::getViolations($e));
                } catch (Throwable $e) {
                    $result = $e->getMessage();
                }

                $results[] = [
                    $i + 1,
                    $createEntry->isChampionship() ? 'Champ.' : 'Cup',
                    $createEntry->getSeason(),
                    $createEntry->getAgeGroup(),
                    $createEntry->getEvent(),
                    $createEntry->getEventDate(),
                    $createEntry->getWinnerClub() . ', ' . $createEntry->getWinnerCountry(),
                    ($createEntry->getHostClub() ?: '')
                    . ($createEntry->getHostCountry() ? ', ' . $createEntry->getHostCountry() : ''),
                    $result,
                ];
                $this->io->progressAdvance();
                $this->clearEntityManager();
            }
            $this->commitTransaction();
        } catch (Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
        $this->io->progressFinish();
        $this->io->table(
            ['#', 'Type', 'Season', 'Age Group', 'Event', 'Date', 'Winner', 'Host'],
            $results
        );
        $this->io->success('Imported ' . $count . ' hall of fame entries.');

        return 0;
    }

    /**
     * @param string $path
     * @return Traversable
     */
    private function readDataFile(string $path): Traversable
    {
        $xml = simplexml_load_string(file_get_contents($path));
        $xml->registerXPathNamespace('h', 'http://www.iishf.com/hallOfFame.xsd');

        $ageGroupMap = [
            'Veterans' => AgeGroup::AGE_GROUP_VETERANS,
            'Men'      => AgeGroup::AGE_GROUP_MEN,
            'Women'    => AgeGroup::AGE_GROUP_WOMEN,
            'U-19'     => AgeGroup::AGE_GROUP_U19,
            'U-16'     => AgeGroup::AGE_GROUP_U16,
            'U-13'     => AgeGroup::AGE_GROUP_U13,
        ];

        foreach ($xml->xpath('//h:year') as $year) {
            /** @var SimpleXMLElement $year */
            foreach ($year->xpath('*') as $event) {
                /** @var SimpleXMLElement $event */

                $event->registerXPathNamespace('h', 'http://www.iishf.com/hallOfFame.xsd');
                $host = $event->xpath('h:host');
                if (count($host) === 1) {
                    $hostClub    = (string)$host[0];
                    $hostCountry = (string)$host[0]['country'];
                    $eventDate   = (string)$host[0]['date'];
                } else {
                    $hostClub    = null;
                    $hostCountry = null;
                    $eventDate   = null;
                }

                foreach ($event->xpath('h:standings/h:team[@position=1]') as $team) {
                    yield [
                        'championship'  => $event->getName() === 'championship',
                        'season'        => (int)$year['year'],
                        'ageGroup'      => $ageGroupMap[(string)$event['ageGroup']] ?? (string)$event['ageGroup'],
                        'winnerClub'    => (string)$team,
                        'winnerCountry' => (string)$team['country'],
                        'hostClub'      => $hostClub,
                        'hostCountry'   => $hostCountry,
                        'eventDate'     => $eventDate,
                    ];
                }
            }
        }
    }
}
