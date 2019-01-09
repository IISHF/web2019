<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:18
 */

namespace App\Infrastructure\Document\Form;

use App\Application\Document\Command\UpdateDocument;
use App\Domain\Model\Document\DocumentRepository;
use App\Infrastructure\Form\TagType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateDocumentType
 *
 * @package App\Infrastructure\Document\Form
 */
class UpdateDocumentType extends AbstractType
{
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * @param DocumentRepository $documentRepository
     */
    public function __construct(DocumentRepository $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

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
                'tags',
                TagType::class,
                [
                    'label'         => 'Tags',
                    'required'      => false,
                    'choice_loader' => new CallbackChoiceLoader(
                        function () {
                            $tags = $this->documentRepository->findAvailableTags();
                            return array_combine($tags, $tags);
                        }
                    ),
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
                'data_class' => UpdateDocument::class,
            ]
        );
    }
}
