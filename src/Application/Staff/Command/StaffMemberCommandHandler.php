<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 09:34
 */

namespace App\Application\Staff\Command;

use App\Application\Common\Command\CommandHandler;
use App\Domain\Model\Staff\StaffMember;
use App\Domain\Model\Staff\StaffMemberRepository;

/**
 * Class StaffMemberCommandHandler
 *
 * @package App\Application\Staff\Command
 */
abstract class StaffMemberCommandHandler implements CommandHandler
{
    /**
     * @var StaffMemberRepository
     */
    protected $memberRepository;

    /**
     * @param StaffMemberRepository $memberRepository
     */
    public function __construct(StaffMemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * @param string $id
     * @return StaffMember
     */
    protected function getStaffMember(string $id): StaffMember
    {
        $member = $this->memberRepository->findById($id);
        if (!$member) {
            throw new \OutOfBoundsException('No staff member found for id ' . $id);
        }
        return $member;
    }
}
