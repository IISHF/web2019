<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:42
 */

namespace App\Infrastructure\Staff\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class StaffMemberType
 *
 * @package App\Infrastructure\Staff\Form
 */
class StaffMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstName',
                TextType::class,
                [
                    'label'    => 'First Name',
                    'required' => true,
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label'    => 'Last Name',
                    'required' => true,
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'label'    => 'E-mail',
                    'required' => true,
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label'    => 'Title',
                    'required' => true,
                ]
            )
            ->add(
                'roles',
                StaffMemberRoleType::class,
                [
                    'label'    => 'Roles',
                    'required' => false,
                ]
            );
    }
}
