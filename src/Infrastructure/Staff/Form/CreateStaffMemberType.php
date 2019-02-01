<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:44
 */

namespace App\Infrastructure\Staff\Form;

use App\Application\Staff\Command\CreateStaffMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateStaffMemberType
 *
 * @package App\Infrastructure\Staff\Form
 */
class CreateStaffMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => CreateStaffMember::class,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return StaffMemberType::class;
    }
}
