<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:11
 */

namespace App\Application\Committee\Command;

use App\Domain\Common\Urlizer;
use App\Domain\Model\Committee\Committee;
use App\Domain\Model\Committee\CommitteeMember;
use App\Domain\Model\Committee\CommitteeRepository;
use OutOfBoundsException;

/**
 * Class CommitteeCommandHandler
 *
 * @package App\Application\Committee\Command
 */
abstract class CommitteeCommandHandler
{
    /**
     * @var CommitteeRepository
     */
    protected $committeeRepository;

    /**
     * @param CommitteeRepository $committeeRepository
     */
    public function __construct(CommitteeRepository $committeeRepository)
    {
        $this->committeeRepository = $committeeRepository;
    }

    /**
     * @param string $id
     * @return Committee
     */
    protected function getCommittee(string $id): Committee
    {
        $committee = $this->committeeRepository->findById($id);
        if (!$committee) {
            throw new OutOfBoundsException('No committee found for id ' . $id);
        }
        return $committee;
    }

    /**
     * @param string $id
     * @return CommitteeMember
     */
    protected function getCommitteeMember(string $id): CommitteeMember
    {
        $member = $this->committeeRepository->findMemberById($id);
        if (!$member) {
            throw new OutOfBoundsException('No committee member found for id ' . $id);
        }
        return $member;
    }

    /**
     * @param string      $title
     * @param string|null $id
     * @return string
     */
    protected function findSuitableCommitteeSlug(string $title, ?string $id): string
    {
        return Urlizer::urlizeUnique(
            $title,
            function (string $slug) use ($id) {
                return ($tryCommittee = $this->committeeRepository->findBySlug($slug)) !== null
                    && $tryCommittee->getId() !== $id;
            }
        );
    }
}
