<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 11:22
 */

namespace App\Infrastructure\Article\Form;

use App\Domain\Model\Article\ArticleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TagType
 *
 * @package App\Infrastructure\Article\Form
 */
class TagType extends AbstractType implements DataTransformerInterface
{
    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

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
                'choice_loader'             => new CallbackChoiceLoader(
                    function () {
                        $tags = $this->articleRepository->findAvailableTags();
                        return array_combine($tags, $tags);
                    }
                ),
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
