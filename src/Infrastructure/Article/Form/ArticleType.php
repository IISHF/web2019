<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-04
 * Time: 12:20
 */

namespace App\Infrastructure\Article\Form;

use App\Infrastructure\Form\TrixEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ArticleType
 *
 * @package App\Infrastructure\Article\Form
 */
class ArticleType extends AbstractType
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
                'subtitle',
                TextType::class,
                [
                    'label'    => 'Subtitle',
                    'required' => false,
                ]
            )
            ->add(
                'tags',
                ArticleTagType::class,
                [
                    'label'    => 'Tags',
                    'required' => false,
                ]
            )
            ->add(
                'body',
                TrixEditorType::class,
                [
                    'label'         => 'Body',
                    'required'      => true,
                    'empty_data'    => '',
                    'enable_upload' => 'app_article_upload',
                ]
            )
            ->add(
                'publishedAt',
                DateTimeType::class,
                [
                    'label'             => 'Published at',
                    'required'          => true,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy HH:mm',
                    'enable_datepicker' => true,
                ]
            );
    }
}
