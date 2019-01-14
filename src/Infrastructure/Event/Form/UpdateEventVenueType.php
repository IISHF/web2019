<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:08
 */

namespace App\Infrastructure\Event\Form;

use App\Application\Event\Command\UpdateEventVenue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateEventVenueType
 *
 * @package App\Infrastructure\Event\Form
 */
class UpdateEventVenueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateEventVenue::class,
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
