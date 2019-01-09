<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:18
 */

namespace App\Infrastructure\Document\Form;

use App\Application\Document\Command\UpdateDocumentVersion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateDocumentVersionType
 *
 * @package App\Infrastructure\Document\Form
 */
class UpdateDocumentVersionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'version',
                TextType::class,
                [
                    'label'    => 'Version',
                    'required' => true,
                ]
            )
            ->add(
                'validFrom',
                DateType::class,
                [
                    'label'             => 'Valid From',
                    'required'          => false,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy',
                    'enable_datepicker' => true,
                ]
            )
            ->add(
                'validUntil',
                DateType::class,
                [
                    'label'             => 'Valid Until',
                    'required'          => false,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy',
                    'enable_datepicker' => true,
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
                'data_class' => UpdateDocumentVersion::class,
            ]
        );
    }
}
