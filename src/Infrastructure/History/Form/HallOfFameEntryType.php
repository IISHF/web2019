<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-06
 * Time: 07:38
 */

namespace App\Infrastructure\History\Form;

use App\Infrastructure\Common\Form\AgeGroupChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HallOfFameEntryType
 *
 * @package App\Infrastructure\History\Form
 */
class HallOfFameEntryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'season',
                IntegerType::class,
                [
                    'label'    => 'Season',
                    'required' => true,
                ]
            )
            ->add(
                'championship',
                ChoiceType::class,
                [
                    'label'    => 'Event Type',
                    'choices'  => [
                        'Championship' => true,
                        'Cup'          => false,
                    ],
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                ]
            )
            ->add(
                'ageGroup',
                AgeGroupChoiceType::class,
                [
                    'label'    => 'Age Group',
                    'required' => true,
                ]
            )
            ->add(
                'event',
                TextType::class,
                [
                    'label'    => 'Event',
                    'required' => true,
                ]
            )
            ->add(
                'eventDate',
                TextType::class,
                [
                    'label'    => 'Event Date',
                    'help'     => 'Something like "May 2019" or "September/October 2018".',
                    'required' => false,
                ]
            )
            ->add(
                'winnerClub',
                TextType::class,
                [
                    'label'    => 'Winning Club',
                    'required' => true,
                ]
            )
            ->add(
                'winnerCountry',
                CountryType::class,
                [
                    'label'          => 'Winning Club\'s Country',
                    'required'       => true,
                    'enable_select2' => true,
                ]
            )
            ->add(
                'secondPlaceClub',
                TextType::class,
                [
                    'label'    => 'Second Place Club',
                    'required' => false,
                ]
            )
            ->add(
                'secondPlaceCountry',
                CountryType::class,
                [
                    'label'          => 'Second Place Club\'s Country',
                    'required'       => false,
                    'enable_select2' => true,
                ]
            )
            ->add(
                'thirdPlaceClub',
                TextType::class,
                [
                    'label'    => 'Third Place Club',
                    'required' => false,
                ]
            )
            ->add(
                'thirdPlaceCountry',
                CountryType::class,
                [
                    'label'          => 'Third Place Club\'s Country',
                    'required'       => false,
                    'enable_select2' => true,
                ]
            )
            ->add(
                'hostClub',
                TextType::class,
                [
                    'label'    => 'Hosting Club',
                    'required' => false,
                ]
            )
            ->add(
                'hostCountry',
                CountryType::class,
                [
                    'label'          => 'Hosting Club\'s Country',
                    'required'       => false,
                    'enable_select2' => true,
                ]
            );
    }
}
