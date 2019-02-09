<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-06
 * Time: 09:39
 */

namespace App\Infrastructure\Committee\Form;

use App\Domain\Model\Committee\TermType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TermTypeChoiceType
 *
 * @package App\Infrastructure\Committee\Form
 */
class TermTypeChoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choices'                   => array_flip(TermType::getTermTypes()),
                'multiple'                  => false,
                'choice_translation_domain' => false,
                'expanded'                  => true,
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
