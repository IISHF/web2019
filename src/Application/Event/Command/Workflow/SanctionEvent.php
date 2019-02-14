<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:55
 */

namespace App\Application\Event\Command\Workflow;

use App\Application\Event\Command\SanctioningProperties;

/**
 * Class SanctionEvent
 *
 * @package App\Application\Event\Command\Workflow
 */
class SanctionEvent extends EventWorkflowCommand
{
    use SanctioningProperties;

    public const TRANSITION = 'sanction';

    /**
     * @param string $id
     */
    protected function __construct(string $id)
    {
        parent::__construct($id, self::TRANSITION);
    }
}
