<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:00
 */

namespace App\Application\Event\Command\Workflow;

use App\Domain\Model\Event\Application\TitleEventApplication;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AnnounceTitleEvent
 *
 * @package App\Application\Event\Command\Workflow
 */
class AnnounceTitleEvent extends EventWorkflowCommand
{
    public const TRANSITION = 'announce';

    /**
     * @Assert\Type("App\Domain\Model\Event\Application\TitleEventApplication")
     * @Assert\NotNull()
     *
     * @var TitleEventApplication
     */
    private $application;

    /**
     * @param string $id
     */
    protected function __construct(string $id)
    {
        parent::__construct($id, self::TRANSITION);
    }

    /**
     * @return TitleEventApplication|null
     */
    public function getApplication(): ?TitleEventApplication
    {
        return $this->application;
    }

    /**
     * @param TitleEventApplication $application
     * @return $this
     */
    public function setApplication(TitleEventApplication $application): self
    {
        $this->application = $application;
        return $this;
    }
}
