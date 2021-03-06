<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:49
 */

namespace App\Infrastructure\Event\Form\Workflow;

use App\Application\Event\Command\Workflow\SanctionEvent;
use App\Infrastructure\Event\Form\SanctioningType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SanctionEventType
 *
 * @package App\Infrastructure\Event\Form\Workflow
 */
class SanctionEventType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'sanctioning',
                SanctioningType::class,
                [
                    'inherit_data' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => SanctionEvent::class,
            ]
        );
    }
}
