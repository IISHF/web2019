<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:51
 */

namespace App\Infrastructure\NationalGoverningBody\Form;

use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class NationalGoverningBodyType
 *
 * @package App\Infrastructure\NationalGoverningBody\Form
 */
class NationalGoverningBodyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label'    => 'Name',
                    'required' => true,
                ]
            )
            ->add(
                'acronym',
                TextType::class,
                [
                    'label'    => 'Acronym',
                    'required' => true,
                ]
            )
            ->add(
                'iocCode',
                TextType::class,
                [
                    'label'    => 'IOC Code',
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
                'email',
                EmailType::class,
                [
                    'label'    => 'E-mail',
                    'required' => true,
                ]
            )
            ->add(
                'website',
                UrlType::class,
                [
                    'label'    => 'Website',
                    'required' => false,
                ]
            )
            ->add(
                'phoneNumber',
                PhoneNumberType::class,
                [
                    'label'    => 'Telephone Number',
                    'required' => false,
                ]
            )
            ->add(
                'facebookProfile',
                TextType::class,
                [
                    'label'    => 'Facebook Profile',
                    'required' => false,
                ]
            )
            ->add(
                'twitterProfile',
                TextType::class,
                [
                    'label'    => 'Twitter Profile',
                    'required' => false,
                ]
            )
            ->add(
                'instagramProfile',
                TextType::class,
                [
                    'label'    => 'Instagram Profile',
                    'required' => false,
                ]
            );
    }
}
