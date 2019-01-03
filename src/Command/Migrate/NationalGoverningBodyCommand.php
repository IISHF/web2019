<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 14:14
 */

namespace App\Command\Migrate;

use App\Application\NationalGoverningBody\Command\CreateNationalGoverningBody;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Class NationalGoverningBodyCommand
 *
 * @package App\Command\Migrate
 */
class NationalGoverningBodyCommand extends BaseCommand
{
    private const COUNTRY_MAP = [
        'GER' => ['DE', 'GER'],
        'UK'  => ['GB', 'GBR'],
        'DEN' => ['DK', 'DEN'],
        'SUI' => ['CH', 'SUI'],
        'ESP' => ['ES', 'ESP'],
        'NED' => ['NL', 'NED'],
        'AUT' => ['AT', 'AUT'],
        'ISR' => ['IL', 'ISR'],
        'RUS' => ['RU', 'RUS'],
        'CRO' => ['HR', 'CRO'],
        'PAK' => ['PK', 'PAK'],
        'UKR' => ['UA', 'UKR'],
        'IND' => ['IN', 'IND'],
    ];

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('app:migrate:ngb')
            ->setDescription('Migrates national governing bodies from legacy database.')
            ->setHelp('This command allows you to migrate national governing bodies from a IISHF legacy database.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $this->io->title('Migrate national governing bodies rom legacy database');

        $phoneUtil = PhoneNumberUtil::getInstance();

        $ngbs = $this->db->fetchAll(
            'SELECT abbr, ngb_name, ngb_abbr, ngb_web, ngb_email, ngb_phone FROM mem_countries'
        );
        $this->io->progressStart(\count($ngbs));
        $results = [];
        foreach ($ngbs as $i => $ngb) {

            $phoneString = $ngb['ngb_phone'];
            if (!empty($phoneString)) {
                try {
                    $phoneString = preg_replace('/[^0-9+]/', '', $phoneString);
                    $phoneNumber = $phoneUtil->parse($phoneString);
                } catch (NumberParseException $e) {
                    $phoneNumber = null;
                }
            } else {
                $phoneNumber = null;
            }

            [$isoCode, $iocCode] = self::COUNTRY_MAP[$ngb['abbr']] ?? ['', ''];

            $createNgb = CreateNationalGoverningBody::create()
                                                    ->setName($ngb['ngb_name'])
                                                    ->setAcronym($ngb['ngb_abbr'])
                                                    ->setIocCode($iocCode)
                                                    ->setCountry($isoCode)
                                                    ->setEmail($ngb['ngb_email'] ?? '')
                                                    ->setPhoneNumber($phoneNumber)
                                                    ->setWebsite($ngb['ngb_web']);

            try {
                $this->dispatchCommand($createNgb);
                $results[] = [
                    $i + 1,
                    $createNgb->getName() . ' (' . $createNgb->getAcronym() . ')',
                    $createNgb->getCountry() . ' / ' . $createNgb->getIocCode() . ' ' . $createNgb->getCountryName(),
                    ($phoneNumber ? $phoneUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL) : '-')
                    . PHP_EOL . $createNgb->getEmail()
                    . PHP_EOL . ($createNgb->getWebsite() ?? '-'),
                ];
            } catch (ValidationFailedException $e) {
                $results[] = [
                    $i + 1,
                    $createNgb->getName() . ' (' . $createNgb->getAcronym() . ')',
                    new TableCell(
                        implode(
                            PHP_EOL,
                            array_map(
                                function (ConstraintViolationInterface $violation) {
                                    return $violation->getPropertyPath() . ': ' . $violation->getMessage();
                                },
                                iterator_to_array($e->getViolations())
                            )
                        ),
                        ['colspan' => 2]
                    ),

                ];
            } catch (\Throwable $e) {
                $results[] = [
                    $i + 1,
                    $createNgb->getName() . ' (' . $createNgb->getAcronym() . ')',
                    new TableCell($e->getMessage(), ['colspan' => 2]),
                ];
            }


            $this->io->progressAdvance();
        }
        $this->io->progressFinish();
        $this->io->table(
            ['#', 'Name', 'Country', 'Contact'],
            $results
        );

        return 0;
    }
}
