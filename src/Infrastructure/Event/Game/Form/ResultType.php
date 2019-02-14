<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:57
 */

namespace App\Infrastructure\Event\Game\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ResultType
 *
 * @package App\Infrastructure\Event\Game\Form
 */
class ResultType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'homeGoals',
                IntegerType::class,
                [
                    'label'    => 'Home Goals',
                    'required' => $options['required'],
                ]
            )
            ->add(
                'awayGoals',
                IntegerType::class,
                [
                    'label'    => 'Away Goals',
                    'required' => $options['required'],
                ]
            );
    }
}
