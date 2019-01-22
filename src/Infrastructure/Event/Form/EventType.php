<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:28
 */

namespace App\Infrastructure\Event\Form;

use App\Application\Event\Command\HasSanctionStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class EventType
 *
 * @package App\Infrastructure\Event\Form
 */
class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label'    => 'Name',
                    'required' => true,
                ]
            )
            ->add(
                'season',
                IntegerType::class,
                [
                    'label'    => 'Season',
                    'scale'    => 0,
                    'required' => true,
                ]
            )
            ->add(
                'ageGroup',
                AgeGroupChoiceType::class,
                [
                    'label'    => 'Age Group',
                    'required' => true,
                ]
            )
            ->add(
                'tags',
                EventTagType::class,
                [
                    'label'    => 'Tags',
                    'required' => false,
                ]
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $eventCommand = $event->getData();
                if (!$eventCommand instanceof HasSanctionStatus
                    || !$eventCommand->isSanctioned()) {
                    return;
                }
                $event->getForm()
                      ->add(
                          'sanctionNumber',
                          TextType::class,
                          [
                              'label'    => 'Sanction Number',
                              'required' => true,
                          ]
                      );
            }
        );
    }
}
