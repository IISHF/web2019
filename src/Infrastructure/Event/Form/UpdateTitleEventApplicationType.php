<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:35
 */

namespace App\Infrastructure\Event\Form;

use App\Application\Event\Command\UpdateTitleEventApplication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateTitleEventApplicationType
 *
 * @package App\Infrastructure\Event\Form
 */
class UpdateTitleEventApplicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateTitleEventApplication::class,
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
