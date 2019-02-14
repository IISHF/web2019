<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:51
 */

namespace App\Infrastructure\Event\Form\EuropeanChampionship;

use App\Application\Event\Command\UpdateEuropeanChampionship;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateEuropeanChampionshipType
 *
 * @package App\Infrastructure\Event\Form\EuropeanChampionship
 */
class UpdateEuropeanChampionshipType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateEuropeanChampionship::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EuropeanChampionshipType::class;
    }
}
