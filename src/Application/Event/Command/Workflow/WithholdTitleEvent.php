<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-21
 * Time: 07:44
 */

namespace App\Application\Event\Command\Workflow;

/**
 * Class WithholdTitleEvent
 *
 * @package App\Application\Event\Command\Workflow
 */
class WithholdTitleEvent extends EventWorkflowCommand
{
    public const TRANSITION = 'withhold';

    /**
     * @param string $id
     */
    protected function __construct(string $id)
    {
        parent::__construct($id, self::TRANSITION);
    }
}
