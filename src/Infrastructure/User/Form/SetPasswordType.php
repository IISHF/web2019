<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 14:28
 */

namespace App\Infrastructure\User\Form;

use App\Application\User\SetPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SetPasswordType
 *
 * @package App\Infrastructure\User\Form
 */
class SetPasswordType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'newPassword',
                RepeatedType::class,
                [
                    'type'           => PasswordType::class,
                    'first_options'  => [
                        'label' => 'New Password',
                    ],
                    'second_options' => [
                        'label' => 'Repeat Password',
                    ],
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
                'data_class' => SetPassword::class,
            ]
        );
    }
}
