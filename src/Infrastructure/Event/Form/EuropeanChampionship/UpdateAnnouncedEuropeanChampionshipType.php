<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:51
 */

namespace App\Infrastructure\Event\Form\EuropeanChampionship;

use App\Application\Event\Command\EuropeanChampionship\UpdateAnnouncedEuropeanChampionship;
use App\Infrastructure\Event\Form\HostingType;
use App\Infrastructure\Event\Form\TitleEventType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateAnnouncedEuropeanChampionshipType
 *
 * @package App\Infrastructure\Event\Form\EuropeanChampionship
 */
class UpdateAnnouncedEuropeanChampionshipType extends AbstractType
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
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateAnnouncedEuropeanChampionship::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TitleEventType::class;
    }
}
