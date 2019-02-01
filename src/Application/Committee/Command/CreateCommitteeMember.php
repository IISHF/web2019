<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 11:08
 */

namespace App\Application\Committee\Command;

use App\Application\Common\Command\UuidAware;

/**
 * Class CreateCommitteeMember
 *
 * @package App\Application\Committee\Command
 */
class CreateCommitteeMember
{
    use UuidAware, CommitteeMemberProperties;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $committeeId;

    /**
     * @param string $committeeId
     * @return self
     */
    public static function create(string $committeeId): self
    {
        return new self(self::createUuid(), $committeeId);
    }

    /**
     * @param string $id
     * @param string $committeeId
     */
    private function __construct(string $id, string $committeeId)
    {
        $this->id          = $id;
        $this->committeeId = $committeeId;
    }

    /**
     * @return string
     */
    public function getCommitteeId(): string
    {
        return $this->committeeId;
    }
}
