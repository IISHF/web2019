<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:31
 */

namespace App\Infrastructure\Event\Form;

use App\Infrastructure\Common\Form\ContactPersonType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TitleEventApplicationType
 *
 * @package App\Infrastructure\Event\Form
 */
class TitleEventApplicationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'applicantClub',
                TextType::class,
                [
                    'label'    => 'Applying Club',
                    'required' => true,
                ]
            )
            ->add(
                'address',
                ContactPersonType::class,
                [
                    'label'        => 'Contact',
                    'by_reference' => true,
                ]
            )
            ->add(
                'proposedStartDate',
                DateType::class,
                [
                    'label'             => 'Proposed Start Date',
                    'required'          => true,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy',
                    'enable_datepicker' => true,
                ]
            )
            ->add(
                'proposedEndDate',
                DateType::class,
                [
                    'label'             => 'Proposed End Date',
                    'required'          => true,
                    'input'             => 'datetime_immutable',
                    'format'            => 'MMMM d, yyyy',
                    'enable_datepicker' => true,
                ]
            )
            ->add(
                'venue',
                VenueChoiceType::class,
                [
                    'label'    => 'Venue',
                    'required' => true,
                ]
            );
    }
}
