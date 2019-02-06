<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-06
 * Time: 07:41
 */

namespace App\Infrastructure\HallOfFame\Form;

use App\Application\HallOfFame\Command\UpdateHallOfFameEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateHallOfFameEntryType
 *
 * @package App\Infrastructure\HallOfFame\Form
 */
class UpdateHallOfFameEntryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateHallOfFameEntry::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return HallOfFameEntryType::class;
    }
}
