<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:42
 */

namespace App\Infrastructure\Event\Form\Tournament;

use App\Infrastructure\Event\Form\EventType;
use App\Infrastructure\Event\Form\HostingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TournamentType
 *
 * @package App\Infrastructure\Event\Form\Tournament
 */
class TournamentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'hosting',
                HostingType::class,
                [
                    'inherit_data' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EventType::class;
    }
}
