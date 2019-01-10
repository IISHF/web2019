<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-04
 * Time: 12:21
 */

namespace App\Infrastructure\Article\Form;

use App\Application\Article\Command\UpdateArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateArticleType
 *
 * @package App\Infrastructure\Article\Form
 */
class UpdateArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['add_published_date']) {
            $builder
                ->add(
                    'publishedAt',
                    DateTimeType::class,
                    [
                        'label'             => 'Published at',
                        'required'          => true,
                        'input'             => 'datetime_immutable',
                        'format'            => 'MMMM d, yyyy HH:mm',
                        'enable_datepicker' => true,
                        'help'              => 'Please be aware that the publish time is UTC.',
                    ]
                );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'         => UpdateArticle::class,
                'add_published_date' => false,
            ]
        );

        $resolver->setAllowedTypes('add_published_date', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return ArticleType::class;
    }
}
