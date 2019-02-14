<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:52
 */

namespace App\Infrastructure\Event\Form\EuropeanCup;

use App\Application\Event\Command\EuropeanCup\UpdateAnnouncedEuropeanCup;
use App\Infrastructure\Event\Form\HostingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateAnnouncedEuropeanCupType
 *
 * @package App\Infrastructure\Event\Form\EuropeanCup
 */
class UpdateAnnouncedEuropeanCupType extends AbstractType
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
                'data_class' => UpdateAnnouncedEuropeanCup::class,
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
