<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 12:55
 */

namespace App\Infrastructure\Article\Form;

use App\Application\Article\Command\PublishArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PublishArticleType
 *
 * @package App\Infrastructure\Article\Form
 */
class PublishArticleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'publishAt',
                DateTimeType::class,
                [
                    'label'             => 'Publish at',
                    'required'          => true,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy HH:mm',
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
                'data_class' => PublishArticle::class,
            ]
        );
    }
}
