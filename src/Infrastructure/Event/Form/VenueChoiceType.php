<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:37
 */

namespace App\Infrastructure\Event\Form;

use App\Domain\Model\Event\EventVenueRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VenueChoiceType
 *
 * @package App\Infrastructure\Event\Form
 */
class VenueChoiceType extends AbstractType
{
    /**
     * @var EventVenueRepository
     */
    private $venueRepository;

    /**
     * @param EventVenueRepository $venueRepository
     */
    public function __construct(EventVenueRepository $venueRepository)
    {
        $this->venueRepository = $venueRepository;
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
                        foreach ($this->venueRepository->findAll() as $venue) {
                            yield $venue->getName() => $venue->getId();
                        }
                    }
                ),
                'choice_translation_domain' => false,
                'multiple'                  => false,
                'enable_select2'            => true,
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
}
