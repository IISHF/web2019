<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:24
 */

namespace App\Infrastructure\Committee\Form;

use App\Infrastructure\Form\TrixEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class CommitteeType
 *
 * @package App\Infrastructure\Committee\Form
 */
class CommitteeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label'    => 'Title',
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
}
