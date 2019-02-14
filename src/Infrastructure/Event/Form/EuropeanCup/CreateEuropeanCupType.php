<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:41
 */

namespace App\Infrastructure\Event\Form\EuropeanCup;

use App\Application\Event\Command\EuropeanCup\CreateEuropeanCup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateEuropeanCupType
 *
 * @package App\Infrastructure\Event\Form\EuropeanCup
 */
class CreateEuropeanCupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateEuropeanCup::class,
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
