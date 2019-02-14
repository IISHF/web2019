<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:45
 */

namespace App\Infrastructure\Event\Game\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class RecordResultType
 *
 * @package App\Infrastructure\Event\Game\Form
 */
class RecordResultType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'result',
                ResultType::class,
                [
                    'inherit_data' => true,
                    'required'     => false,
                ]
            );
    }
}
