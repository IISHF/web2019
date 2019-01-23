<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:36
 */

namespace App\Infrastructure\Event\Form;

use App\Application\Event\Command\HasAnnouncementStatus;
use App\Infrastructure\Form\TrixEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class TitleEventType
 *
 * @package App\Infrastructure\Event\Form
 */
class TitleEventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plannedLength',
                IntegerType::class,
                [
                    'label'    => 'Planned Length (Days)',
                    'scale'    => 0,
                    'required' => true,
                ]
            )
            ->add(
                'description',
                TrixEditorType::class,
                [
                    'label'         => 'Description',
                    'required'      => false,
                    'enable_upload' => null,
                ]
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $eventCommand = $event->getData();
                if (!$eventCommand instanceof HasAnnouncementStatus
                    || !$eventCommand->isAnnounced()) {
                    return;
                }
                $event->getForm()
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
                      );
            }
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
