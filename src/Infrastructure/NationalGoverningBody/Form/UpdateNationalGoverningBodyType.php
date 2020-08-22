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
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(PreSetDataEvent $event): void
    {
        /** @var UpdateNationalGoverningBody $data */
        $data = $event->getData();
        if (!$data->hasLogo()) {
            return;
        }
        $form = $event->getForm();
        $form->add(
            'removeLogo',
            CheckboxType::class,
            [
                'label' => 'Remove logo',
                'help'  => 'This checkbox is mutually exclusive with uploading a new logo',
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
