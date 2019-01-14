<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:32
 */

namespace App\Infrastructure\Event\Form;

use App\Domain\Model\Event\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AgeGroupChoiceType
 *
 * @package App\Infrastructure\Event\Form
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
                'choices'                   => [
                    'Veterans'    => Event::AGE_GROUP_VETERANS,
                    'Men'         => Event::AGE_GROUP_MEN,
                    'Women'       => Event::AGE_GROUP_WOMEN,
                    'U19 Junior'  => Event::AGE_GROUP_U19,
                    'U16 Youth'   => Event::AGE_GROUP_U16,
                    'U13 Pee-Wee' => Event::AGE_GROUP_U13,
                ],
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
