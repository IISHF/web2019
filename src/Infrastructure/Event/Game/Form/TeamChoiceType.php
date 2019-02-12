<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 10:04
 */

namespace App\Infrastructure\Event\Game\Form;

use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\Team\ParticipatingTeamRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TeamChoiceType
 *
 * @package App\Infrastructure\Event\Game\Form
 */
class TeamChoiceType extends AbstractType
{
    /**
     * @var ParticipatingTeamRepository
     */
    private $teamRepository;

    /**
     * @param ParticipatingTeamRepository $teamRepository
     */
    public function __construct(ParticipatingTeamRepository $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choice_loader'             => function (Options $options) {
                    return new CallbackChoiceLoader(
                        function () use ($options) {
                            foreach ($this->teamRepository->findForEvent($options['event']) as $team) {
                                yield $team->getName() => $team->getId();
                            }
                        }
                    );
                },
                'choice_translation_domain' => false,
                'multiple'                  => false,
                'enable_select2'            => true,
            ]
        );
        $resolver->setRequired('event');
        $resolver->setAllowedTypes('event', Event::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return ChoiceType::class;
    }
}
