<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:40
 */

namespace App\Infrastructure\Event\Form\EuropeanCup;

use App\Infrastructure\Event\Form\TitleEventType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class EuropeanCupType
 *
 * @package App\Infrastructure\Event\Form\EuropeanCup
 */
class EuropeanCupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'plannedTeams',
                IntegerType::class,
                [
                    'label'    => 'Planned Number of Teams',
                    'required' => true,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TitleEventType::class;
    }
}
