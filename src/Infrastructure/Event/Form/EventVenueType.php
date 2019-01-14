<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:06
 */

namespace App\Infrastructure\Event\Form;

use App\Infrastructure\Common\Form\AddressType;
use App\Infrastructure\Form\TrixEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EventVenueType
 *
 * @package App\Infrastructure\Event\Form
 */
class EventVenueType extends AbstractType
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
                'address',
                AddressType::class,
                [
                    'label'        => 'Address',
                    'by_reference' => true,
                ]
            )
            ->add(
                'rinkInfo',
                TrixEditorType::class,
                [
                    'label'         => 'Rink Information',
                    'required'      => false,
                    'enable_upload' => false,
                ]
            );
    }
}
