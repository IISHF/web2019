<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:32
 */

namespace App\Infrastructure\Common\Form;

use App\Domain\Common\AgeGroup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AgeGroupChoiceType
 *
 * @package App\Infrastructure\Common\Form;
 */
class AgeGroupChoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choices'                   => array_flip(AgeGroup::getAgeGroups()),
                'multiple'                  => false,
                'choice_translation_domain' => false,
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
