<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:32
 */

namespace App\Infrastructure\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TagType
 *
 * @package App\Infrastructure\Form
 */
class TagType extends AbstractType implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->resetViewTransformers()
                ->addViewTransformer($this, true);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choice_translation_domain' => false,
                'multiple'                  => true,
                'enable_select2'            => true,
                'select2_options'           => function (Options $options, $previousValue) {
                    return array_merge(
                        [
                            'tokenSeparators' => [','],
                        ],
                        $previousValue,
                        [
                            'tags' => true,
                        ]
                    );
                },
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if ($value === null) {
            return [];
        }

        if (!\is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        return array_unique($value);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return [];
        }

        if (!\is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        return array_unique($value);
    }
}
