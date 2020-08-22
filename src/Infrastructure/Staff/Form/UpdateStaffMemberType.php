<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:44
 */

namespace App\Infrastructure\Staff\Form;

use App\Application\Staff\Command\UpdateStaffMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateStaffMemberType
 *
 * @package App\Infrastructure\Staff\Form
 */
class UpdateStaffMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateStaffMember::class,
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
        /** @var UpdateStaffMember $data */
        $data = $event->getData();
        if (!$data->hasImage()) {
            return;
        }
        $form = $event->getForm();
        $form->add(
            'removeImage',
            CheckboxType::class,
            [
                'label' => 'Remove profile image',
                'help'  => 'This checkbox is mutually exclusive with uploading a new image',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return StaffMemberType::class;
    }
}
