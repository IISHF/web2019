<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:39
 */

namespace App\Infrastructure\Event\Form\EuropeanChampionship;

use App\Application\Event\Command\EuropeanChampionship\CreateEuropeanChampionship;
use App\Infrastructure\Event\Form\TitleEventType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateEuropeanChampionshipType
 *
 * @package App\Infrastructure\Event\Form\EuropeanChampionship
 */
class CreateEuropeanChampionshipType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateEuropeanChampionship::class,
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
