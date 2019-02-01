<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:44
 */

namespace App\Infrastructure\Staff\Form;

use App\Application\Staff\Command\UpdateStaffMember;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UpdateStaffMemberType
 *
 * @package App\Infrastructure\Staff\Form
 */
class UpdateStaffMemberType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => UpdateStaffMember::class,
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
