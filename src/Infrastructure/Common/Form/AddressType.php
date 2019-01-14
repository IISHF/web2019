<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:13
 */

namespace App\Infrastructure\Common\Form;

use App\Application\Common\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddressType
 *
 * @package App\Infrastructure\Common\Form
 */
class AddressType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'address1',
                TextType::class,
                [
                    'label'    => 'Address 1',
                    'required' => false,
                ]
            )
            ->add(
                'address2',
                TextType::class,
                [
                    'label'    => 'Address 2',
                    'required' => false,
                ]
            )
            ->add(
                'state',
                TextType::class,
                [
                    'label'    => 'State',
                    'required' => false,
                ]
            )
            ->add(
                'postalCode',
                TextType::class,
                [
                    'label'    => 'Postal Code',
                    'required' => false,
                ]
            )
            ->add(
                'city',
                TextType::class,
                [
                    'label'    => 'City',
                    'required' => true,
                ]
            )
            ->add(
                'country',
                CountryType::class,
                [
                    'label'    => 'Country',
                    'required' => true,
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
                'data_class' => Address::class,
            ]
        );
    }
}
