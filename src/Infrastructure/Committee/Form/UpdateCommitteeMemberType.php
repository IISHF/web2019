<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:26
 */

namespace App\Infrastructure\Committee\Form;

use App\Application\Committee\Command\UpdateCommitteeMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateCommitteeMemberType
 *
 * @package App\Infrastructure\Committee\Form
 */
class UpdateCommitteeMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateCommitteeMember::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return CommitteeMemberType::class;
    }
}
