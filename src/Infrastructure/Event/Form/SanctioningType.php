<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 09:44
 */

namespace App\Infrastructure\Event\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SanctioningType
 *
 * @package App\Infrastructure\Event\Form
 */
class SanctioningType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'sanctionNumber',
                TextType::class,
                [
                    'label'    => 'Sanction Number',
                    'required' => true,
                ]
            );
    }
}
