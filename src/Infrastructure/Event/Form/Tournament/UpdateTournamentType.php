<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:54
 */

namespace App\Infrastructure\Event\Form\Tournament;

use App\Application\Event\Command\UpdateTournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateTournamentType
 *
 * @package App\Infrastructure\Event\Form\Tournament
 */
class UpdateTournamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateTournament::class,
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
