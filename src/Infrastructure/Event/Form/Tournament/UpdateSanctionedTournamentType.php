<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:42
 */

namespace App\Infrastructure\Event\Form\Tournament;

use App\Application\Event\Command\Tournament\UpdateSanctionedTournament;
use App\Infrastructure\Event\Form\SanctioningType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateSanctionedTournamentType
 *
 * @package App\Infrastructure\Event\Form\Tournament
 */
class UpdateSanctionedTournamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'sanctioning',
                SanctioningType::class,
                [
                    'inherit_data' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateSanctionedTournament::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TournamentType::class;
    }
}
