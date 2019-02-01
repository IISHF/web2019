<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 10:15
 */

namespace App\Infrastructure\Staff\Form;

use App\Domain\Model\Staff\StaffMemberRepository;
use App\Infrastructure\Form\TagType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class StaffMemberRoleType
 *
 * @package App\Infrastructure\Staff\Form
 */
class StaffMemberRoleType extends AbstractType
{
    /**
     * @var StaffMemberRepository
     */
    private $memberRepository;

    /**
     * @param StaffMemberRepository $memberRepository
     */
    public function __construct(StaffMemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'tag_provider' => $this->memberRepository,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TagType::class;
    }
}
