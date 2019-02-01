<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:26
 */

namespace App\Infrastructure\Committee\Form;

use App\Application\Committee\Command\UpdateCommittee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateCommitteeType
 *
 * @package App\Infrastructure\Committee\Form
 */
class UpdateCommitteeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateCommittee::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return CommitteeType::class;
    }
}
