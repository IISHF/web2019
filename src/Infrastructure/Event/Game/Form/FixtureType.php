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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FixtureType
 *
 * @package App\Infrastructure\Event\Game\Form
 */
class FixtureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'homeTeamIsProvisional',
                ChoiceType::class,
                [
                    'label'    => 'Home Team',
                    'choices'  => [
                        'Team'             => false,
                        'Provisional Team' => true,
                    ],
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                ]
            )
            ->add(
                'homeTeam',
                TeamChoiceType::class,
                [
                    'label'    => 'Home Team',
                    'required' => false,
                    'event'    => $options['event'],
                ]
            )
            ->add(
                'homeTeamProvisional',
                TextType::class,
                [
                    'label'    => 'Provisional Home Team',
                    'required' => false,
                ]
            )
            ->add(
                'awayTeamIsProvisional',
                ChoiceType::class,
                [
                    'label'    => 'Away Team',
                    'choices'  => [
                        'Team'             => false,
                        'Provisional Team' => true,
                    ],
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                ]
            )
            ->add(
                'awayTeam',
                TeamChoiceType::class,
                [
                    'label'    => 'Away Team',
                    'required' => false,
                    'event'    => $options['event'],
                ]
            )
            ->add(
                'awayTeamProvisional',
                TextType::class,
                [
                    'label'    => 'Provisional Away Team',
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
