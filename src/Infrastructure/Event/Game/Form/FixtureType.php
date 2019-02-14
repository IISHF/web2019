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
                'homeTeam',
                TeamChoiceType::class,
                [
                    'label'    => 'Home Team',
                    'required' => $options['required'],
                    'event'    => $options['event'],
                ]
            )
            ->add(
                'awayTeam',
                TeamChoiceType::class,
                [
                    'label'    => 'Away Team',
                    'required' => $options['required'],
                    'event'    => $options['event'],
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
