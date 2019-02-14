<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:46
 */

namespace App\Infrastructure\Event\Game\Form;

use App\Domain\Model\Event\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RescheduleGameType
 *
 * @package App\Infrastructure\Event\Game\Form
 */
class RescheduleGameType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Event $event */
        $event = $options['event'];
        $builder
            ->add(
                'schedule',
                ScheduleType::class,
                [
                    'time_zone'    => $event->getTimeZone()->getName(),
                    'required'     => true,
                    'inherit_data' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('event');
        $resolver->setAllowedTypes('event', Event::class);
    }
}
