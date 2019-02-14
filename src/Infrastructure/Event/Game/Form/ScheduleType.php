<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:57
 */

namespace App\Infrastructure\Event\Game\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ScheduleType
 *
 * @package App\Infrastructure\Event\Game\Form
 */
class ScheduleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'dateTime',
                DateTimeType::class,
                [
                    'label'             => 'Date/Time',
                    'required'          => $options['required'],
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy HH:mm',
                    'model_timezone'    => $options['time_zone'],
                    'view_timezone'     => $options['time_zone'],
                    'enable_datepicker' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('time_zone');
        $resolver->setAllowedTypes('time_zone', 'string');
    }
}
