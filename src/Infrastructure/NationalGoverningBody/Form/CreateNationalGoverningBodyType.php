<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:51
 */

namespace App\Infrastructure\NationalGoverningBody\Form;

use App\Application\NationalGoverningBody\Command\CreateNationalGoverningBody;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateNationalGoverningBodyType
 *
 * @package App\Infrastructure\NationalGoverningBody\Form
 */
class CreateNationalGoverningBodyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateNationalGoverningBody::class,
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
