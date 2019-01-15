<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-02
 * Time: 13:40
 */

namespace App\Infrastructure\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class TrixEditorType
 *
 * @package App\Infrastructure\Form
 */
class TrixEditorType extends AbstractType
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'enable_upload' => null,
            ]
        );
        $resolver->setAllowedTypes('enable_upload', ['string', 'null']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($options['enable_upload'] !== null) {
            $view->vars['enable_upload'] = $this->urlGenerator->generate($options['enable_upload']);
        } else {
            $view->vars['enable_upload'] = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): string
    {
        return TextareaType::class;
    }
}
