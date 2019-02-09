<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:58
 */

namespace App\Infrastructure\Event\Game\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class GameTypeChoiceType
 *
 * @package App\Infrastructure\Event\Game\Form
 */
class GameTypeChoiceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choices'                   => array_flip(\App\Domain\Model\Event\Game\GameType::getGameTypes()),
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
