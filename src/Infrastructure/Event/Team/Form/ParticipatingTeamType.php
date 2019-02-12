<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:19
 */

namespace App\Infrastructure\Event\Team\Form;

use App\Infrastructure\Common\Form\ContactPersonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ParticipatingTeamType
 *
 * @package App\Infrastructure\Event\Team\Form
 */
class ParticipatingTeamType extends AbstractType
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
                'contact',
                ContactPersonType::class,
                [
                    'label'        => 'Contact',
                    'by_reference' => true,
                ]
            );
    }
}
