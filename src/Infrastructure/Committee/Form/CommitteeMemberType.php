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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                    'label'          => 'Country',
                    'required'       => true,
                    'enable_select2' => true,
                ]
            )
            ->add(
                'memberType',
                MemberTypeChoiceType::class,
                [
                    'label'    => 'Member Type',
                    'required' => true,
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label'    => 'Title',
                    'required' => false,
                    'help'     => 'You can provide a more specific title here. If empty the title will default to the member type.',
                ]
            )
            ->add(
                'termType',
                TermTypeChoiceType::class,
                [
                    'label'    => 'Term Type',
                    'required' => true,
                ]
            )
            ->add(
                'termSince',
                IntegerType::class,
                [
                    'label'    => 'Term since',
                    'required' => false,
                ]
            )
            ->add(
                'termDuration',
                IntegerType::class,
                [
                    'label'    => 'Term Duration',
                    'required' => false,
                ]
            )
            ->add(
                'firstTerm',
                IntegerType::class,
                [
                    'label'    => 'First Term',
                    'required' => false,
                    'help'     => 'This can be used to store the first term a committee member served. This is especially useful if the position is up for regular relection.',
                ]
            )
            ->add(
                'image',
                FileType::class,
                [
                    'label'    => 'Profile Image',
                    'required' => false,
                ]
            );
    }
}
