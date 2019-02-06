<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-06
 * Time: 07:38
 */

namespace App\Infrastructure\HallOfFame\Form;

use App\Infrastructure\Event\Form\AgeGroupChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class HallOfFameEntryType
 *
 * @package App\Infrastructure\HallOfFame\Form
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
                    'scale'    => 0,
                    'required' => true,
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
                    'label'    => 'Winning Club\'s Country',
                    'required' => true,
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
                    'label'    => 'Hosting Club\'s Country',
                    'required' => false,
                ]
            );
    }
}
