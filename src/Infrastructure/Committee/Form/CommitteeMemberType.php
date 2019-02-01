<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:24
 */

namespace App\Infrastructure\Committee\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CommitteeMemberType
 *
 * @package App\Infrastructure\Committee\Form
 */
class CommitteeMemberType extends AbstractType
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
                'country',
                CountryType::class,
                [
                    'label'    => 'Country',
                    'required' => true,
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label'    => 'Title',
                    'required' => false,
                ]
            );
    }
}
