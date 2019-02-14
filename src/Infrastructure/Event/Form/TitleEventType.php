<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:36
 */

namespace App\Infrastructure\Event\Form;

use App\Infrastructure\Form\TrixEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TitleEventType
 *
 * @package App\Infrastructure\Event\Form
 */
class TitleEventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plannedLength',
                IntegerType::class,
                [
                    'label'    => 'Planned Length (Days)',
                    'required' => true,
                ]
            )
            ->add(
                'description',
                TrixEditorType::class,
                [
                    'label'         => 'Description',
                    'required'      => false,
                    'enable_upload' => null,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return EventType::class;
    }
}
