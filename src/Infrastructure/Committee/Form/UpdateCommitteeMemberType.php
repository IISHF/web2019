<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:26
 */

namespace App\Infrastructure\Committee\Form;

use App\Application\Committee\Command\UpdateCommitteeMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateCommitteeMemberType
 *
 * @package App\Infrastructure\Committee\Form
 */
class UpdateCommitteeMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateCommitteeMember::class,
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

    /**
     * @param PreSetDataEvent $event
     */
    public function onPreSetData(PreSetDataEvent $event): void
    {
        /** @var UpdateCommitteeMember $data */
        $data = $event->getData();
        if (!$data->hasImage()) {
            return;
        }
        $form = $event->getForm();
        $form->add(
            'removeImage',
            CheckboxType::class,
            [
                'label'    => 'Remove profile image',
                'required' => false,
                'help'     => 'This checkbox is mutually exclusive with uploading a new image',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return CommitteeMemberType::class;
    }
}
