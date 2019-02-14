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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
        $event    = $options['event'];
        $timeZone = $event->getTimeZone()->getName();
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
                'dateTime',
                DateTimeType::class,
                [
                    'label'             => 'Date/Time',
                    'required'          => true,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy HH:mm',
                    'model_timezone'    => $timeZone,
                    'view_timezone'     => $timeZone,
                    'enable_datepicker' => true,
                ]
            )
            ->add(
                'homeTeam',
                TeamChoiceType::class,
                [
                    'label'    => 'Home Team',
                    'required' => true,
                    'event'    => $options['event'],
                ]
            )
            ->add(
                'awayTeam',
                TeamChoiceType::class,
                [
                    'label'    => 'Away Team',
                    'required' => true,
                    'event'    => $options['event'],
                ]
            )
            ->add(
                'remarks',
                TextType::class,
                [
                    'label'    => 'Remarks',
                    'required' => false,
                ]
            )
            ->add(
                'homeGoals',
                IntegerType::class,
                [
                    'label'    => 'Home Goals',
                    'required' => false,
                ]
            )
            ->add(
                'awayGoals',
                IntegerType::class,
                [
                    'label'    => 'Away Goals',
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
