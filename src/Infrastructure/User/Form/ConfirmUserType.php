<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-29
 * Time: 16:55
 */

namespace App\Infrastructure\User\Form;

use App\Application\User\Command\ConfirmUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ConfirmUserType
 *
 * @package App\Infrastructure\User\Form
 */
class ConfirmUserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'password',
                RepeatedType::class,
                [
                    'type'           => PasswordType::class,
                    'first_options'  => [
                        'label' => 'Password',
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
                'data_class' => ConfirmUser::class,
            ]
        );
    }
}
