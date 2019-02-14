<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:52
 */

namespace App\Infrastructure\Event\Form\EuropeanCup;

use App\Application\Event\Command\UpdateEuropeanCup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateEuropeanCupType
 *
 * @package App\Infrastructure\Event\Form\EuropeanCup
 */
class UpdateEuropeanCupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateEuropeanCup::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EuropeanCupType::class;
    }
}
