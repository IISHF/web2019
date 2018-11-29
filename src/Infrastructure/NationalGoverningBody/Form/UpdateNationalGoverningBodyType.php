<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:53
 */

namespace App\Infrastructure\NationalGoverningBody\Form;

use App\Application\NationalGoverningBody\Command\UpdateNationalGoverningBody;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateNationalGoverningBodyType
 *
 * @package App\Infrastructure\NationalGoverningBody\Form
 */
class UpdateNationalGoverningBodyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateNationalGoverningBody::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return NationalGoverningBodyType::class;
    }
}
