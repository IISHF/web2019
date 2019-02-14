<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:48
 */

namespace App\Infrastructure\Event\Form\Tournament;

use App\Application\Event\Command\CreateTournament;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateTournamentType
 *
 * @package App\Infrastructure\Event\Form\Tournament
 */
class CreateTournamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateTournament::class,
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
