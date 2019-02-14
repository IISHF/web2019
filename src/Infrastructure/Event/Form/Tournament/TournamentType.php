<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:42
 */

namespace App\Infrastructure\Event\Form\Tournament;

use App\Infrastructure\Event\Form\EventHostType;
use App\Infrastructure\Event\Form\EventType;
use App\Infrastructure\Event\Form\VenueChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TournamentType
 *
 * @package App\Infrastructure\Event\Form\Tournament
 */
class TournamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'host',
                EventHostType::class,
                [
                    'label'        => 'Host',
                    'by_reference' => true,
                ]
            )
            ->add(
                'startDate',
                DateType::class,
                [
                    'label'             => 'Start Date',
                    'required'          => true,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy',
                    'enable_datepicker' => true,
                ]
            )
            ->add(
                'endDate',
                DateType::class,
                [
                    'label'             => 'End Date',
                    'required'          => true,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy',
                    'enable_datepicker' => true,
                ]
            )
            ->add(
                'venue',
                VenueChoiceType::class,
                [
                    'label'    => 'Venue',
                    'required' => true,
                ]
            )
            ->add(
                'timeZone',
                TimezoneType::class,
                [
                    'label'          => 'Time Zone',
                    'input'          => 'datetimezone',
                    'required'       => true,
                    'enable_select2' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EventType::class;
    }
}
