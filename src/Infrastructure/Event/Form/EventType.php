<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:28
 */

namespace App\Infrastructure\Event\Form;

use App\Infrastructure\Common\Form\AgeGroupChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EventType
 *
 * @package App\Infrastructure\Event\Form
 */
class EventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label'    => 'Name',
                    'required' => true,
                ]
            )
            ->add(
                'season',
                IntegerType::class,
                [
                    'label'    => 'Season',
                    'required' => true,
                ]
            )
            ->add(
                'ageGroup',
                AgeGroupChoiceType::class,
                [
                    'label'    => 'Age Group',
                    'required' => true,
                ]
            )
            ->add(
                'tags',
                EventTagType::class,
                [
                    'label'    => 'Tags',
                    'required' => false,
                ]
            );
    }
}
