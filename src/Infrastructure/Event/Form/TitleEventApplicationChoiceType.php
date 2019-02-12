<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:15
 */

namespace App\Infrastructure\Event\Form;

use App\Domain\Model\Event\Application\TitleEventApplicationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TitleEventApplicationChoiceType
 *
 * @package App\Infrastructure\Event\Form
 */
class TitleEventApplicationChoiceType extends AbstractType
{
    /**
     * @var TitleEventApplicationRepository
     */
    private $applicationRepository;

    /**
     * @param TitleEventApplicationRepository $applicationRepository
     */
    public function __construct(TitleEventApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choice_loader'             => function (Options $options) {
                    $titleEventId = $options['title_event_id'];
                    return new CallbackChoiceLoader(
                        function () use ($titleEventId) {
                            return $this->applicationRepository->findForEventId($titleEventId);
                        }
                    );
                },
                'choice_translation_domain' => false,
                'choice_value'              => 'id',
                'choice_label'              => 'applicantClub',
                'multiple'                  => false,
                'enable_select2'            => true,
            ]
        );
        $resolver->setRequired('title_event_id');
        $resolver->setAllowedTypes('title_event_id', 'string');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
