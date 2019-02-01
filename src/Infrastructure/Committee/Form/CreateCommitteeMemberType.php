<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:26
 */

namespace App\Infrastructure\Committee\Form;

use App\Application\Committee\Command\CreateCommitteeMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateCommitteeMemberType
 *
 * @package App\Infrastructure\Committee\Form
 */
class CreateCommitteeMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateCommitteeMember::class,
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
