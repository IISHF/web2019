<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:52
 */

namespace App\Infrastructure\Event\Form\EuropeanCup;

use App\Application\Event\Command\EuropeanCup\UpdateSanctionedEuropeanCup;
use App\Infrastructure\Event\Form\HostingType;
use App\Infrastructure\Event\Form\SanctioningType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateSanctionedEuropeanCupType
 *
 * @package App\Infrastructure\Event\Form\EuropeanCup
 */
class UpdateSanctionedEuropeanCupType extends AbstractType
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
            )
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
                'data_class' => UpdateSanctionedEuropeanCup::class,
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
