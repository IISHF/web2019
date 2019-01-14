<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:55
 */

namespace App\Application\Event\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SanctionEvent
 *
 * @package App\Application\Event\Command
 */
class SanctionEvent extends EventWorkflowCommand
{
    public const TRANSITION = 'sanction';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=16)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $sanctionNumber;

    /**
     * @param string $id
     */
    protected function __construct(string $id)
    {
        parent::__construct($id, self::TRANSITION);
    }

    /**
     * @return string
     */
    public function getSanctionNumber(): string
    {
        return $this->sanctionNumber;
    }

    /**
     * @param string $sanctionNumber
     * @return $this
     */
    public function setSanctionNumber(string $sanctionNumber): self
    {
        $this->sanctionNumber = $sanctionNumber;
        return $this;
    }


}
