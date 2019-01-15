<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:32
 */

namespace App\Infrastructure\Form;

use App\Domain\Model\Common\TagProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceListView;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
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
        $builder->addViewTransformer($this);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'compound' => false,
                'multiple' => true,
            ]
        );
        $resolver->setRequired('tag_provider');
        $resolver->setAllowedTypes('tag_provider', TagProvider::class);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        /** @var TagProvider $tagProvider */
        $tagProvider    = $options['tag_provider'];
        $choiceListView = new ChoiceListView(
            array_map(
                function (string $tag) {
                    return new ChoiceView($tag, $tag, $tag);
                },
                array_unique(array_merge($tagProvider->findAvailableTags(), $form->getViewData()))
            ),
            []
        );

        $view->vars = array_replace(
            $view->vars,
            [
                'choices'                   => $choiceListView->choices,
                'preferred_choices'         => $choiceListView->preferredChoices,
                'choice_translation_domain' => false,
                'placeholder'               => null,
                'multiple'                  => true,
            ]
        );

        $view->vars['full_name']       .= '[]';
        $view->vars['is_selected']     = function ($choice, array $values) {
            return \in_array($choice, $values, true);
        };
        $view->vars['select2_options'] = [
            'tokenSeparators' => [','],
            'tags'            => true,
        ];
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
