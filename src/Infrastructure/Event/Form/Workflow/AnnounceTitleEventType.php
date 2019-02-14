<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:20
 */

namespace App\Infrastructure\Event\Form\Workflow;

use App\Application\Event\Command\Workflow\AnnounceTitleEvent;
use App\Infrastructure\Event\Form\TitleEventApplicationChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AnnounceTitleEventType
 *
 * @package App\Infrastructure\Event\Form\Workflow
 */
class AnnounceTitleEventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var AnnounceTitleEvent $announceTitleEvent */
                $announceTitleEvent = $event->getData();
                $titleEventId       = $announceTitleEvent->getId();
                $event->getForm()
                      ->add(
                          'application',
                          TitleEventApplicationChoiceType::class,
                          [
                              'label'          => 'Application',
                              'required'       => true,
                              'title_event_id' => $titleEventId,
                          ]
                      );
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => AnnounceTitleEvent::class,
            ]
        );
    }
}
