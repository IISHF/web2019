<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 09:47
 */

namespace App\Infrastructure\Event\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HostingType
 *
 * @package App\Infrastructure\Event\Form
 */
class HostingType extends AbstractType
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
}
