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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'publishNow',
                ChoiceType::class,
                [
                    'label'    => false,
                    'choices'  => [
                        'Now'        => true,
                        'Publish at' => false,
                    ],
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                ]
            )
            ->add(
                'publishAt',
                DateTimeType::class,
                [
                    'label'             => false,
                    'required'          => true,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy HH:mm',
                    'enable_datepicker' => true,
                    'help'              => 'Please be aware that the publish time is UTC.',
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
