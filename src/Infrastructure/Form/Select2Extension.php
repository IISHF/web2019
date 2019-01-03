<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 10:40
 */

namespace App\Infrastructure\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Select2Extension
 *
 * @package App\Infrastructure\Form
 */
class Select2Extension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes()
    {
        return [ChoiceType::class];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'enable_select2'  => false,
                'select2_options' => [],
                'expanded'        => function (Options $options, $previousValue) {
                    return $options['enable_select2'] ? false : $previousValue;
                },
            ]
        );
        $resolver->setAllowedTypes('enable_select2', 'bool');
        $resolver->setAllowedTypes('select2_options', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$options['enable_select2']) {
            return;
        }

        $view->vars['enable_select2']  = true;
        $view->vars['select2_options'] = $options['select2_options'];
    }
}
