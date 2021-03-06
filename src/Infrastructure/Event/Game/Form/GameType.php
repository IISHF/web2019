<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:57
 */

namespace App\Infrastructure\Event\Game\Form;

use App\Domain\Model\Event\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GameType
 *
 * @package App\Infrastructure\Event\Game\Form
 */
class GameType extends AbstractType
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
                'gameType',
                GameTypeChoiceType::class,
                [
                    'label'    => 'Game Type',
                    'required' => true,
                ]
            )
            ->add(
                'schedule',
                ScheduleType::class,
                [
                    'time_zone'    => $event->getTimeZone()->getName(),
                    'required'     => true,
                    'inherit_data' => true,
                ]
            )
            ->add(
                'fixture',
                FixtureType::class,
                [
                    'event'        => $event,
                    'required'     => true,
                    'inherit_data' => true,
                ]
            )
            ->add(
                'remarks',
                TextType::class,
                [
                    'label'    => 'Remarks',
                    'required' => false,
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
