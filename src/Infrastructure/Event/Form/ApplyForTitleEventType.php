<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:34
 */

namespace App\Infrastructure\Event\Form;

use App\Application\Event\Command\ApplyForTitleEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ApplyForTitleEventType
 *
 * @package App\Infrastructure\Event\Form
 */
class ApplyForTitleEventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => ApplyForTitleEvent::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TitleEventApplicationType::class;
    }
}
