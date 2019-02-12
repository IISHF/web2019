<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:35
 */

namespace App\Infrastructure\Event\Application\Form;

use App\Application\Event\Application\Command\UpdateTitleEventApplication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateTitleEventApplicationType
 *
 * @package App\Infrastructure\Event\Application\Form
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
