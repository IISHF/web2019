<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:08
 */

namespace App\Infrastructure\Event\Form;

use App\Application\Event\Command\CreateEventVenue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateEventVenueType
 *
 * @package App\Infrastructure\Event\Form
 */
class CreateEventVenueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateEventVenue::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EventVenueType::class;
    }
}
