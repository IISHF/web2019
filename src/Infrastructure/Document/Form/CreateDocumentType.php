<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:18
 */

namespace App\Infrastructure\Document\Form;

use App\Application\Document\Command\CreateDocument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateDocumentType
 *
 * @package App\Infrastructure\Document\Form
 */
class CreateDocumentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label'    => 'Title',
                    'required' => true,
                ]
            )
            ->add(
                'version',
                TextType::class,
                [
                    'label'    => 'Version',
                    'required' => true,
                ]
            )
            ->add(
                'file',
                FileType::class,
                [
                    'label'    => 'File',
                    'required' => true,
                ]
            )
            ->add(
                'tags',
                DocumentTagType::class,
                [
                    'label'    => 'Tags',
                    'required' => false,
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
                'data_class' => CreateDocument::class,
            ]
        );
    }
}
