<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:21
 */

namespace App\Infrastructure\Event\Form;

use App\Application\Event\Command\UpdateParticipatingTeam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateParticipatingTeamType
 *
 * @package App\Infrastructure\Event\Form
 */
class UpdateParticipatingTeamType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateParticipatingTeam::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return ParticipatingTeamType::class;
    }
}
